<?php

namespace Database\Seeders;

use App\Models\ShippingMethod;
use Illuminate\Database\Seeder;

class TenantShippingMethodSeeder extends Seeder
{
    public function run(): void
    {
        $shippingOptions = [
            'parcel' => [
                'label' => 'Parcel (Standard Shipping)',
                'cost' => 5.00,
                'carriers' => ['PostNL', 'DHL', 'FedEx', 'UPS'],
            ],
            'mail' => [
                'label' => 'Mail (Letter/Small Package)',
                'cost' => 2.50,
                'carriers' => ['PostNL'],
            ],
            'pickup' => [
                'label' => 'Pickup (In-Store Pickup)',
                'cost' => 0.00,
                'carriers' => [],
            ],
            'express' => [
                'label' => 'Express (Faster Parcel Shipping)',
                'cost' => 15.00,
                'carriers' => ['DHL', 'FedEx', 'UPS'],
            ],
            'courier' => [
                'label' => 'Courier (Personal Delivery)',
                'cost' => 25.00,
                'carriers' => ["Yuri's Special Delivery Service"],
            ],
        ];

        foreach ($shippingOptions as $code => $data) {
            ShippingMethod::create([
                'label' => $data['label'],
                'code' => $code,
                'cost' => $data['cost'],
                'carriers' => $data['carriers'],
                'enabled' => true,
            ]);
        }
    }
}
