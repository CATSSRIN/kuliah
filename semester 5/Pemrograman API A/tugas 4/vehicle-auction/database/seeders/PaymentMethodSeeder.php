<?php

namespace Database\Seeders;

use App\Models\PaymentMethod;
use Illuminate\Database\Seeder;

class PaymentMethodSeeder extends Seeder
{
    public function run(): void
    {
        $methods = [
            // Bank Transfer
            ['name' => 'BCA Virtual Account', 'code' => 'bca_va', 'type' => 'virtual_account', 'gateway' => 'midtrans', 'fee_fixed' => 4000, 'fee_percentage' => 0],
            ['name' => 'BNI Virtual Account', 'code' => 'bni_va', 'type' => 'virtual_account', 'gateway' => 'midtrans', 'fee_fixed' => 4000, 'fee_percentage' => 0],
            ['name' => 'BRI Virtual Account', 'code' => 'bri_va', 'type' => 'virtual_account', 'gateway' => 'midtrans', 'fee_fixed' => 4000, 'fee_percentage' => 0],
            ['name' => 'Mandiri Virtual Account', 'code' => 'mandiri_va', 'type' => 'virtual_account', 'gateway' => 'midtrans', 'fee_fixed' => 4000, 'fee_percentage' => 0],
            
            // E-Wallet
            ['name' => 'GoPay', 'code' => 'gopay', 'type' => 'ewallet', 'gateway' => 'midtrans', 'fee_fixed' => 0, 'fee_percentage' => 2],
            ['name' => 'ShopeePay', 'code' => 'shopeepay', 'type' => 'ewallet', 'gateway' => 'midtrans', 'fee_fixed' => 0, 'fee_percentage' => 2],
            ['name' => 'OVO', 'code' => 'ovo', 'type' => 'ewallet', 'gateway' => 'xendit', 'fee_fixed' => 0, 'fee_percentage' => 2. 5],
            ['name' => 'DANA', 'code' => 'dana', 'type' => 'ewallet', 'gateway' => 'xendit', 'fee_fixed' => 0, 'fee_percentage' => 1.5],
            ['name' => 'LinkAja', 'code' => 'linkaja', 'type' => 'ewallet', 'gateway' => 'xendit', 'fee_fixed' => 0, 'fee_percentage' => 1.5],
            
            // Credit Card
            ['name' => 'Credit Card', 'code' => 'credit_card', 'type' => 'credit_card', 'gateway' => 'midtrans', 'fee_fixed' => 2000, 'fee_percentage' => 2. 9],
        ];

        foreach ($methods as $index => $method) {
            PaymentMethod::create(array_merge($method, ['sort_order' => $index]));
        }
    }
}