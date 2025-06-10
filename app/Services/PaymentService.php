<?php

namespace App\Services;

use App\Models\CustomerPaymentAccount;
use App\Models\Payment;

class PaymentService
{
    public static function savePayment(int $orderId, int $customerId, array $paymentData): Payment
    {
        $payment = Payment::create([
            'order_id' => $orderId,
            'customer_id' => $customerId,
            'payment_method' => $paymentData['payment_method'] ?? 'unknown',
            'transaction_id' => $paymentData['transaction_id'],
            'amount' => $paymentData['amount'],
            'status' => $paymentData['status'],
        ]);

        if (!empty($paymentData['provider']) && !empty($paymentData['provider_customer_id'])) {
            CustomerPaymentAccount::updateOrCreate(
                [
                    'customer_id' => $customerId,
                    'provider' => $paymentData['provider']
                ],
                [
                    'provider_customer_id' => $paymentData['provider_customer_id']
                ]
            );
        }

        return $payment;
    }
}
