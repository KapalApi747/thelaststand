<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 8px 0;
        }

        tr + tr td {
            border-top: 1px solid #eaeaea;
        }

        .total {
            font-weight: bold;
        }

        .button {
            display: inline-block;
            background-color: #2d3748;
            color: #fff;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
<h1>Order Confirmation</h1>

<p>Hi {{ $order->customer->name }},</p>

<p>
    Thanks for your order <strong>#{{ $order->order_number }}</strong> placed on <strong>{{ $order->created_at->format('F j, Y') }}</strong>.
</p>

<h2>Order Summary</h2>

<table>
    @foreach ($order->items as $item)
        <tr>
            <td>{{ $item->product_name }} × {{ $item->quantity }}</td>
            <td align="right">€{{ number_format($item->price * $item->quantity, 2) }}</td>
        </tr>
    @endforeach
    <tr class="total">
        <td>Total:</td>
        <td align="right">€{{ number_format($order->total_amount, 2) }}</td>
    </tr>
</table>

<p>
    <a href="{{ route('customer-order-view', $order->order_number) }}" class="button">View Your Order</a>
</p>

<p>We will ship your items shortly.</p>

<p>If you have any questions, feel free to reply to this email.</p>

<p>Kind regards,<br>{{ tenant()->store_name }}</p>
</body>
</html>
