<? php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@vehicleauction.com',
            'phone' => '081234567890',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'status' => 'active',
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Demo Member',
            'email' => 'member@vehicleauction.com',
            'phone' => '081234567891',
            'password' => Hash::make('password123'),
            'role' => 'member',
            'status' => 'active',
            'balance' => 10000000,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Demo Anggota',
            'email' => 'anggota@vehicleauction. com',
            'phone' => '081234567892',
            'password' => Hash::make('password123'),
            'role' => 'anggota',
            'status' => 'active',
            'balance' => 5000000,
            'email_verified_at' => now(),
        ]);
    }
}