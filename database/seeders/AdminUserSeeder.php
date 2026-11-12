<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@lpbeauty.test'],
            [
                'name' => 'Admin LP Beauty',
                'password' => 'AdminBeauty123!',
                'role' => 'admin',
            ]
        );
    }
}