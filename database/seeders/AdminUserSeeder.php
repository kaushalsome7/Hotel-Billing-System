<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@billifystay.com'],
            [
                'name'     => 'Admin',
                'email'    => 'admin@billifystay.com',
                'password' => Hash::make('password'),
            ]
        );

        $this->command->info('Admin user created: admin@billifystay.com / password');
    }
}
