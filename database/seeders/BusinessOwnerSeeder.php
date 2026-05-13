<?php

namespace Database\Seeders;

use App\Models\User;
use App\Enums\UserRole;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class BusinessOwnerSeeder extends Seeder
{
    public function run(): void
    {
        $owners = [
            [
                'name' => 'Mario Rossi',
                'email' => 'owner.bodega@example.com',
                'preferred_language' => 'es',
                'preferred_theme' => 'light',
            ],
            [
                'name' => 'Jan Janssens',
                'email' => 'owner.cafevlaanderen@example.com',
                'preferred_language' => 'nl',
                'preferred_theme' => 'dark',
            ],
            [
                'name' => 'Maria Garcia',
                'email' => 'owner.marazul@example.com',
                'preferred_language' => 'es',
                'preferred_theme' => 'light',
            ],
            [
                'name' => 'John Smith',
                'email' => 'manager.beachbar@example.com',
                'preferred_language' => 'en',
                'preferred_theme' => 'dark',
            ],
            [
                'name' => 'Jose Pepe',
                'email' => 'owner.casapepe@example.com',
                'preferred_language' => 'es',
                'preferred_theme' => 'light',
            ],
        ];

        foreach ($owners as $data) {
            User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password'),
                    'role' => UserRole::OWNER,
                    'preferred_language' => $data['preferred_language'],
                    'preferred_theme' => $data['preferred_theme'],
                    'email_verified_at' => now(),
                ]
            );
        }
    }
}
