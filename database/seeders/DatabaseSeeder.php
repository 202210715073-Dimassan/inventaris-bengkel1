<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed default admin user
        User::updateOrCreate(
            ['email' => 'admin@mogerzz.com'],
            [
                'name' => 'Admin Mo Gerzz',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'admin',
            ]
        );

        // Seed default owner user
        User::updateOrCreate(
            ['email' => 'owner@mogerzz.com'],
            [
                'name' => 'Owner Mo Gerzz',
                'password' => \Illuminate\Support\Facades\Hash::make('password'),
                'role' => 'owner',
            ]
        );

        $this->call([
            ProductSeeder::class,
            TransactionSeeder::class,
        ]);
    }
}
