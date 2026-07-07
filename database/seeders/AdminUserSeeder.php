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
            ['email' => 'admin@JayaAbadi.com'],
            [
                'name' => 'Admin JayaAbadi',
                'password' => Hash::make('password'),
                'is_admin' => true,
            ]
        );
    }
}