<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@sanctuari.com'],
            [
                'name' => 'Admin Sanctuari',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );
    }
}