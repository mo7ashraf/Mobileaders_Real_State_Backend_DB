<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        Admin::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin',
                'password' => Hash::make('ChangeMe123!'),
                'is_superadmin' => true,
            ],
        );

        Admin::updateOrCreate(
            ['email' => 'editor@example.com'],
            [
                'name' => 'Editor',
                'password' => Hash::make('ChangeMe123!'),
                'is_superadmin' => false,
            ],
        );
    }
}

