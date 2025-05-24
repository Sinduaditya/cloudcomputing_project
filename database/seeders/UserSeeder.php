<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@mediaconverter.com',
            'password' => Hash::make('admin123'),
            'is_admin' => true,
            'token_balance' => 100000,
            'is_active' => true,
        ]);

        // Create default demo user
        User::create([
            'name' => 'Demo User',
            'email' => 'user@mediaconverter.com',
            'password' => Hash::make('user123'),
            'is_admin' => false,
            'token_balance' => 1000,
            'is_active' => true,
        ]);

        // Create premium user
        User::create([
            'name' => 'Premium User',
            'email' => 'premium@mediaconverter.com',
            'password' => Hash::make('premium123'),
            'is_admin' => false,
            'token_balance' => 5000,
            'is_active' => true,
        ]);

        // Create an inactive user
        User::create([
            'name' => 'Inactive User',
            'email' => 'inactive@mediaconverter.com',
            'password' => Hash::make('inactive123'),
            'is_admin' => false,
            'token_balance' => 0,
            'is_active' => false,
        ]);

    }
}
