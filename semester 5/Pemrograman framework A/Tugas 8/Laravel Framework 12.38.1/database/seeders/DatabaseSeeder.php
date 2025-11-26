<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // 1. User Admin (Active)
        User::create([
            'name'      => 'Super Admin',
            'email'     => 'admin@gmail.com',
            'password'  => Hash::make('password'), // passwordnya: password
            'role'      => 'admin',
            'status'    => 'active',
            'nomor_hp'  => '081234567891',
            'alamat'    => 'Jl. Merdeka No. 1, Jakarta'
        ]);

        // 2. User Biasa (Active)
        User::create([
            'name'      => 'Budi Santoso',
            'email'     => 'budi@gmail.com',
            'password'  => Hash::make('password'),
            'role'      => 'user',
            'status'    => 'active',
            'nomor_hp'  => '081298765432',
            'alamat'    => 'Jl. Sudirman No. 45, Bandung'
        ]);

        // 3. User Biasa (Suspended)
        User::create([
            'name'      => 'Siti Aminah',
            'email'     => 'siti@gmail.com',
            'password'  => Hash::make('password'),
            'role'      => 'user',
            'status'    => 'suspended',
            'nomor_hp'  => '081355556666',
            'alamat'    => 'Jl. Melati No. 12, Surabaya'
        ]);

        // 4. Admin Kedua (Active)
        User::create([
            'name'      => 'Andi Pratama',
            'email'     => 'andi@gmail.com',
            'password'  => Hash::make('password'),
            'role'      => 'admin',
            'status'    => 'active',
            'nomor_hp'  => '081812341234',
            'alamat'    => 'Jl. Kenanga, Yogyakarta'
        ]);

        // 5. User Biasa (Active)
        User::create([
            'name'      => 'Dewi Lestari',
            'email'     => 'dewi@gmail.com',
            'password'  => Hash::make('password'),
            'role'      => 'user',
            'status'    => 'active',
            'nomor_hp'  => '085711223344',
            'alamat'    => 'Jl. Diponegoro, Semarang'
        ]);
    }
}