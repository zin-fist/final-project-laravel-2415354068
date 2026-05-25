<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer; // <-- Kita panggil model Customer kamu

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Pengisi Data User Bawaan
        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // 2. Pengisi Data Customers Sesuai Desain Halaman 1 Figma Kamu!
        Customer::create([
            'customer_id' => '021423457',
            'customer_name' => 'Alice Johnson',
            'email' => 'alice@gmail.com',
            'address' => 'Swan Street',
            'status' => 'Active'
        ]);

        Customer::create([
            'customer_id' => '021423458',
            'customer_name' => 'Bob Smith',
            'email' => 'bob@gmail.com',
            'address' => 'Maple Avenue',
            'status' => 'Inactive'
        ]);

        Customer::create([
            'customer_id' => '021423459',
            'customer_name' => 'Carol White',
            'email' => 'carol@gmail.com',
            'address' => 'Pine Road',
            'status' => 'Active'
        ]);
    }
}