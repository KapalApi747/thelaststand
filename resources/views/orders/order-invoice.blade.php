<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body { font-family: sans-serif; font-size: 14px; }
        .heading { font-size: 18px; margin-bottom: 10px; font-weight: bold; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
        .text-right { text-align: right; }
    </style>
</head>
<body>

<h1>Invoice #{{ $order->order_number }}</h1>
<p><strong>Date:</strong> {{ $order->created_at->format('Y-m-d') }}</p>

<div class="heading">Customer Info</div>
@php
    $billing = $order->addresses->where('type', 'billing')->first();
    $shipping = $order->addresses->where('type', 'shipping')->first();
@endphp

<table>
    <tr>
        <th>Billing Address</th>
        <th>Shipping Address</th>
    </tr>
    <tr>
        <td>
            {{ $billing->full_name }}<br>
            {{ $billing->address_line1 }} {{ $billing->address_line2 }}<br>
            {{ $billing->city }}, {{ $billing->state }} {{ $billing->zip }}<br>
            {{ $billing->country }}<br>
            Phone: {{ $billing->phone }}
        </td>
        <td>
            {{ $shipping->full_name }}<br>
            {{ $shipping->address_line1 }} {{ $shipping->address_line2 }}<br>
            {{ $shipping->city }}, {{ $shipping->state }} {{ $shipping->zip }}<br>
            {{ $shipping->country }}<br>
            Phone: {{ $shipping->phone }}
        </td>
    </tr>
</table>

<div class="heading">Order Items</div>
<table>
    <thead>
    <tr>
        <th>Product</th>
        <th class="text-right">Price</th>
        <th class="text-right">Qty</th>
        <th class="text-right">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($order->items as $item)
        <tr>
            <td>{{ $item->product_name }}</td>
            <td class="text-right">€{{ number_format($item->price, 2) }}</td>
            <td class="text-right">{{ $item->quantity }}</td>
            <td class="text-right">€{{ number_format($item->price * $item->quantity, 2) }}</td>
        </tr>
    @endforeach
    </tbody>
</table>

<div class="heading">Summary</div>
<table>
    <tr>
        <td>Subtotal</td>
        <td class="text-right">€{{ number_format($order->total_amount, 2) }}</td>
    </tr>
    <tr>
        <td>Tax (21%)</td>
        <td class="text-right">€{{ number_format($order->tax_amount, 2) }}</td>
    </tr>
    <tr>
        <td><strong>Total</strong></td>
        <td class="text-right"><strong>€{{ number_format($order->total_amount, 2) }}</strong></td>
    </tr>
</table>

<div class="heading">Payment Info</div>
<table>
    <tr>
        <th>Method</th>
        <th>Status</th>
        <th>Amount</th>
        <th>Date</th>
    </tr>
    @foreach($order->payments as $payment)
        <tr>
            <td>{{ $payment->payment_method }}</td>
            <td style="text-transform: uppercase">{{ $payment->status }}</td>
            <td>€{{ number_format($payment->amount, 2) }}</td>
            <td>{{ $payment->created_at->format('Y-m-d') }}</td>
        </tr>
    @endforeach
</table>

<div class="heading">Shipping Info</div>
<table>
    <tr>
        <th>Carrier</th>
        <th>Method</th>
        <th>Tracking</th>
        <th>Status</th>
        <th>Shipped</th>
        <th>Delivered</th>
    </tr>
    @foreach($order->shipments as $shipment)
        <tr>
            <td>{{ $shipment->carrier }}</td>
            <td style="text-transform: uppercase">{{ $shipment->shipping_method }}</td>
            <td>{{ $shipment->tracking_number }}</td>
            <td style="text-transform: uppercase">{{ $shipment->status }}</td>
            <td>{{ optional($shipment->shipped_at)->format('Y-m-d') }}</td>
            <td>{{ optional($shipment->delivered_at)->format('Y-m-d') }}</td>
        </tr>
    @endforeach
</table>

</body>
</html>
