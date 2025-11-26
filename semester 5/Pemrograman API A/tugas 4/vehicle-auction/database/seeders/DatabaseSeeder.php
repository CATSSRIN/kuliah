<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            AdminUserSeeder::class,
            MembershipPackageSeeder::class,
            VehicleBrandSeeder::class,
            PaymentMethodSeeder::class,
            AreaSeeder::class,
        ]);
    }
}