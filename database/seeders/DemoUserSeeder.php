<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\TokenTransaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Default token balance for new users
        $defaultBalance = 100; // Sesuai dengan default di migrasi

        // Demo users dengan informasi berbeda
        $demoUsers = [
            [
                'name' => 'John Doe',
                'email' => 'john@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'Jane Smith',
                'email' => 'jane@example.com',
                'password' => 'password123'
            ],
            [
                'name' => 'Demo User',
                'email' => 'demo@example.com',
                'password' => 'demo123'
            ],
            [
                'name' => 'Student User',
                'email' => 'student@ugm.ac.id',
                'password' => 'student123'
            ],
        ];

        foreach ($demoUsers as $userData) {
            // Create user
            $user = User::create([
                'name' => $userData['name'],
                'email' => $userData['email'],
                'password' => Hash::make($userData['password']),
                'token_balance' => $defaultBalance,
                'is_admin' => false,
                'is_active' => true,
            ]);

            // Record token transaction
            TokenTransaction::create([
                'user_id' => $user->id,
                'amount' => $defaultBalance,
                'type' => 'initial',
                'description' => 'Initial token balance for new user',
                'balance_after' => $defaultBalance,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info('Demo users created successfully!');
    }
}
