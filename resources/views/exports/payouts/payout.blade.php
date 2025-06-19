<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Payout #{{ $payout->id }}</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ccc; padding: 6px; text-align: left; }
        th { background-color: #f0f0f0; }
    </style>
</head>
<body>
<h2>Payout #{{ $payout->id }}</h2>
<p><strong>Total Payout:</strong> €{{ number_format($payout->amount, 2) }}</p>
<p><strong>Date Paid:</strong> {{ $payout->paid_at->format('Y-m-d') }}</p>

<table>
    <thead>
    <tr>
        <th>Order #</th>
        <th>Order Total (€)</th>
        <th>Payout (5%) (€)</th>
        <th>Order Date</th>
        <th>Customer</th>
        <th>Email</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>{{ number_format($order->total_amount, 2) }}</td>
            <td>{{ number_format($order->total_amount * 0.05, 2) }}</td>
            <td>{{ $order->created_at->format('Y-m-d H:i') }}</td>
            <td>{{ optional($order->customer)->name ?? 'N/A' }}</td>
            <td>{{ optional($order->customer)->email ?? 'N/A' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
