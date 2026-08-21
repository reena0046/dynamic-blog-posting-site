<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@blogspace.com'],
            [
                'name' => 'BlogSpace Admin',
                'password' => Hash::make('BlogSpace@123'),
                'is_admin' => true,
                'email_verified_at' => now(),
            ]
        );
    }
}
