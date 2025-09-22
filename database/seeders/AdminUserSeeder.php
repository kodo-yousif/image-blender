<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['username' => 'admin'],
            [
                'password'   => Hash::make('hairAiSecret123@s'),
                'role'       => 'admin',
                'expires_at' => null,
            ]
        );
    }
}
