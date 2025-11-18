<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        User::updateOrCreate(
            ['email' => 'admin@sportykuy.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password123'),
                'role' => User::ROLE_SUPERADMIN
            ]
        );
    }
}
