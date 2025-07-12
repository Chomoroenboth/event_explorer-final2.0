<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::create([
            'email' => 'darith@gmail.com',
            'password' => Hash::make('123456789'),

            'email' => 'both@gmail.com',
            'password' => Hash::make('123456789'),

            'email' => 'Pichchansomanea@gmail.com',
            'password' => Hash::make('123456789'),
            
        ]);
    }
}
