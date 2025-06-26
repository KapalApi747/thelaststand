<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Invoice - {{ $order->order_number }}</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            color: #333;
        }

        table {
            page-break-inside: avoid;
        }

        h3 {
            page-break-after: avoid;
        }

        tr, td, th {
            page-break-inside: avoid;
        }

        .section {
            page-break-inside: avoid;
            page-break-after: auto;
        }
    </style>
</head>
<body style="font-family: Arial, sans-serif; font-size: 14px; color: #333;">

<h2 style="margin-bottom: 5px;">Invoice #{{ $order->order_number }}</h2>
<p style="margin-top: 0; margin-bottom: 20px;"><strong>Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>

<!-- Customer Info -->
<h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px;">Customer Info</h3>
@php
    $billing = $order->addresses->where('type', 'billing')->first();
    $shipping = $order->addresses->where('type', 'shipping')->first();
@endphp
<table width="100%" cellpadding="5" cellspacing="0" border="0" style="margin-bottom: 20px;">
    <tr>
        @if ($billing)
        <td style="vertical-align: top; border: 1px solid #ddd; width: 50%;">
            <strong>Billing Address</strong><br>
            {{ $billing->address_line1 }} {{ $billing->address_line2 }}<br>
            {{ $billing->city }}, {{ $billing->state }} {{ $billing->zip }}<br>
            {{ $billing->country }}<br>
            Phone: {{ $billing->phone }}
        </td>
        @endif
        <td style="vertical-align: top; border: 1px solid #ddd; width: 50%;">
            <strong>Shipping Address</strong><br>
            {{ $shipping->address_line1 }} {{ $shipping->address_line2 }}<br>
            {{ $shipping->city }}, {{ $shipping->state }} {{ $shipping->zip }}<br>
            {{ $shipping->country }}<br>
            Phone: {{ $shipping->phone }}
        </td>
    </tr>
</table>

<!-- Order Items -->
<h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px;">Order Items</h3>
<table width="100%" cellpadding="5" cellspacing="0" border="1" style="border-collapse: collapse; margin-bottom: 20px;">
    <thead>
    <tr style="background: #f9f9f9;">
        <th align="left">Product</th>
        <th align="right">Price</th>
        <th align="right">Qty</th>
        <th align="right">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($order->items as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td align="right">€{{ number_format($item->price, 2) }}</td>
            <td align="right">{{ $item->quantity }}</td>
            <td align="right">€{{ number_format($item->price * $item->quantity, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<!-- Summary -->
<div class="section">
    <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px;">Summary</h3>
    <table width="100%" cellpadding="5" cellspacing="0" border="1" style="margin-bottom: 20px;">
    <tr>
            <td style="border-top: 1px solid #ddd;">Subtotal</td>
            <td style="border-top: 1px solid #ddd;" align="right">€{{ number_format($order->total_amount, 2) }}</td>
        </tr>
        <tr>
            <td>Tax (21%)</td>
            <td align="right">€{{ number_format($order->tax_amount, 2) }}</td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td align="right"><strong>€{{ number_format($order->total_amount, 2) }}</strong></td>
        </tr>
    </table>
</div>

<!-- Payment Info -->
<div class="section">
    <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px;">Payment Info</h3>
    <table width="100%" cellpadding="5" cellspacing="0" border="1"
           style="margin-bottom: 20px; page-break-inside: auto;">
        <thead style="page-break-inside: avoid; page-break-after: auto;">
        <tr style="background: #f9f9f9; page-break-inside: avoid; page-break-after: auto;">
            <th>Method</th>
            <th>Status</th>
            <th>Amount</th>
            <th>Date</th>
        </tr>
        </thead>
        <tbody style="page-break-inside: auto; page-break-after: auto;">
        @foreach($order->payments as $payment)
            <tr style="page-break-inside: avoid; page-break-after: auto;">
                <td>{{ $payment->payment_method }}</td>
                <td style="text-transform: uppercase">{{ ucfirst($payment->status) }}</td>
                <td>€{{ number_format($payment->amount, 2) }}</td>
                <td>{{ $payment->created_at->format('Y-m-d') }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

</div>

<!-- Shipping Info -->
<div class="section">
    <h3 style="border-bottom: 1px solid #ccc; padding-bottom: 5px;">Shipping Info</h3>
    <table width="100%" cellpadding="5" cellspacing="0" border="1" style="margin-bottom: 20px;">

    <thead>
        <tr style="background: #f9f9f9;">
            <th>Carrier</th>
            <th>Method</th>
            <th>Tracking</th>
        </tr>
        </thead>
        <tbody>
        @foreach($order->shipments as $shipment)
            <tr>
                <td>{{ $shipment->carrier }}</td>
                <td style="text-transform: uppercase">{{ $shipment->shipping_method }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
