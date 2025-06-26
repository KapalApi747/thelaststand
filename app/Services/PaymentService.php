<?php

namespace App\Services;

use App\Models\CustomerPaymentAccount;
use App\Models\Payment;

class PaymentService
{

    /**
     * Sla een betaling op in de database en koppel eventueel de klant aan een betaalprovider.
     *
     * @param int   $orderId       Het ID van de bestelling.
     * @param int   $customerId    Het ID van de klant.
     * @param array $paymentData   Gegevens over de betaling.
     *
     * @return Payment
     */

    public static function savePayment(int $orderId, int $customerId, array $paymentData): Payment
    {
        // Maak een nieuwe betaling aan
        $payment = Payment::create([
            'order_id' => $orderId,
            'customer_id' => $customerId,
            'payment_method' => $paymentData['payment_method'] ?? 'unknown',
            'transaction_id' => $paymentData['transaction_id'],
            'amount' => $paymentData['amount'],
            'status' => $paymentData['status'],
        ]);

        // Koppel klant aan betaalprovider als gegevens aanwezig zijn
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
