<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Barangay Chairman
        User::updateOrCreate([
            'first_name' => 'Juan',
            'last_name' => 'Dela Cruz',
            'username' => 'chairman',
            'email' => 'chairman@barangay.com',
            'password' => Hash::make('chairman123'),
            'role' => 'chairman',
            'phone' => '09123456789',
            'address' => 'Barangay Hall, Main Street',
            'status' => 'active',
        ]);

        // Barangay Secretary
        User::updateOrCreate([
            'first_name' => 'Maria',
            'last_name' => 'Santos',
            'username' => 'secretary',
            'email' => 'secretary@barangay.com',
            'password' => Hash::make('secretary123'),
            'role' => 'secretary',
            'phone' => '09234567890',
            'address' => 'Block 1 Lot 5, Village Heights',
            'status' => 'active',
        ]);

        // Barangay Staff 1
        User::updateOrCreate([
            'first_name' => 'Pedro',
            'last_name' => 'Reyes',
            'username' => 'staff1',
            'email' => 'staff1@barangay.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
            'phone' => '09345678901',
            'address' => 'Block 2 Lot 10, Green Valley',
            'status' => 'active',
        ]);

        // Barangay Staff 2
        User::updateOrCreate([
            'first_name' => 'Ana',
            'last_name' => 'Garcia',
            'username' => 'staff2',
            'email' => 'staff2@barangay.com',
            'password' => Hash::make('staff123'),
            'role' => 'staff',
            'phone' => '09456789012',
            'address' => 'Block 3 Lot 15, Sunrise Homes',
            'status' => 'active',
        ]);

        // IT Support (Admin role - optional)
        User::updateOrCreate([
            'first_name' => 'Carlos',
            'last_name' => 'Rodriguez',
            'username' => 'admin',
            'email' => 'admin@barangay.com',
            'password' => Hash::make('admin123'),
            'role' => 'staff', // or create new 'admin' role if needed
            'phone' => '09567890123',
            'address' => 'Block 4 Lot 20, Tech Park',
            'status' => 'active',
        ]);
    }
}
