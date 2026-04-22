<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Buat akun admin default.
     */
    public function run(): void
    {
        $admins = [
            ['name' => 'Administrator', 'email' => 'admin@welcomemanado.com'],
            ['name' => 'Tania', 'email' => 'tania@gmail.com'],
            ['name' => 'Farry', 'email' => 'farry@gmail.com'],
            ['name' => 'Aldo', 'email' => 'aldo@gmail.com'],
            ['name' => 'Casey', 'email' => 'casey@gmail.com'],
        ];

        foreach ($admins as $admin) {
            User::updateOrCreate(
                ['email' => $admin['email']],
                [
                    'name' => $admin['name'],
                    'password' => Hash::make('password'),
                ]
            );
        }
    }
}
