<?php

namespace App\Livewire\Tenant\Backend\Payouts;

use App\Models\Order;
use App\Models\Payout;
use App\Services\CsvExportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Symfony\Component\HttpFoundation\StreamedResponse;

#[Layout('t-dashboard-layout')]
class TenantPayoutView extends Component
{
    public Payout $payout;
    public $orders;

    public function mount($payoutId)
    {
        $this->payout = Payout::on('mysql')->with([])->findOrFail($payoutId);
        $this->orders = Order::with(['customer'])
        ->where('payout_id', $this->payout->id)->get();
    }

    public function payoutCalculation(Order $order): float
    {
        return round($order->total_amount - $order->total_amount * 0.05, 2);
    }

    public function exportCsv(CsvExportService $csv): StreamedResponse
    {
        return $csv->export(
            ['Order ID', 'Order Total (€)', 'Payout (5%) (€)', 'Order Date', 'Customer Name', 'Customer Email'],
            $this->orders,
            fn (Order $order) => [
                $order->id,
                number_format($order->total_amount, 2),
                number_format($order->total_amount * 0.05, 2),
                $order->created_at->format('Y-m-d H:i'),
                optional($order->customer)->name ?? 'N/A',
                optional($order->customer)->email ?? 'N/A',
            ],
            'payout-' . $this->payout->id . '.csv'
        );
    }

    public function exportPdf()
    {
        $pdf = Pdf::loadView('exports.payouts.payout', [
            'payout' => $this->payout,
            'orders' => $this->orders,
        ])->setPaper('a4', 'portrait');

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'payout-' . $this->payout->id . '.pdf'
        );
    }

    public function render()
    {
        return view('livewire.tenant.backend.payouts.tenant-payout-view');
    }
}
