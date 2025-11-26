<?php

namespace Database\Seeders;

use App\Models\MembershipPackage;
use Illuminate\Database\Seeder;

class MembershipPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            [
                'name' => 'Bronze',
                'description' => 'Paket member bronze dengan iklan unlimited selama 30 hari',
                'price' => 99000,
                'duration_days' => 30,
                'max_ads_per_week' => -1,
                'features' => json_encode([
                    'Unlimited iklan',
                    'Badge member',
                    'Prioritas tampil',
                ]),
            ],
            [
                'name' => 'Silver',
                'description' => 'Paket member silver dengan fitur premium selama 90 hari',
                'price' => 249000,
                'duration_days' => 90,
                'max_ads_per_week' => -1,
                'features' => json_encode([
                    'Unlimited iklan',
                    'Badge member silver',
                    'Prioritas tampil tinggi',
                    'Highlight iklan',
                ]),
            ],
            [
                'name' => 'Gold',
                'description' => 'Paket member gold dengan semua fitur premium selama 365 hari',
                'price' => 799000,
                'duration_days' => 365,
                'max_ads_per_week' => -1,
                'features' => json_encode([
                    'Unlimited iklan',
                    'Badge member gold',
                    'Prioritas tampil tertinggi',
                    'Highlight iklan',
                    'Featured di homepage',
                    'Laporan statistik',
                ]),
            ],
        ];

        foreach ($packages as $package) {
            MembershipPackage::create($package);
        }
    }
}