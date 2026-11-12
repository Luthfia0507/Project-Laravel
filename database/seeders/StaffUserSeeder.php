<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class StaffUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'staff@lpbeauty.test'],
            [
                'name' => 'Staff Lp_Beauty',
                'password' => 'StaffBeauty123!',
                'role' => 'staff',
            ]
        );
    }
}
