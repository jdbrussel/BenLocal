<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class ProductionUserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user for Jasper
        User::updateOrCreate(
            ['email' => 'jasper@studiobxl.com'],
            [
                'name' => 'Jasper Brussel',
                'password' => Hash::make('jasperStudioBxl!'),
                'role' => UserRole::ADMIN,
                'email_verified_at' => now(),
            ]
        );

        // Support user (example)
        User::updateOrCreate(
            ['email' => 'support@benlocal.com'],
            [
                'name' => 'BenLocal Support',
                'password' => Hash::make(bin2hex(random_bytes(10))), // Random secure password
                'role' => UserRole::SUPPORT,
                'email_verified_at' => now(),
            ]
        );
    }
}
