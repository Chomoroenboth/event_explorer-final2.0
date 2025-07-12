<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Create multiple admin records
        $admins = [
            [
                'email' => 'darith@gmail.com',
                'password' => Hash::make('123456789'),
            ],
            [
                'email' => 'both@gmail.com',
                'password' => Hash::make('123456789'),
            ],
            [
                'email' => 'Pichchansomanea@gmail.com',
                'password' => Hash::make('123456789'),
            ],
        ];

        foreach ($admins as $admin) {
            Admin::updateOrCreate(
                ['email' => $admin['email']], // Check by email
                $admin                        // Create or update with this data
            );
        }
    }
}