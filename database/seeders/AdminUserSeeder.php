<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\TokenTransaction;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create admin user
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@clouddownloader.com',
            'password' => Hash::make('admin123'),
            'token_balance' => 9999, // Admin mendapat token lebih banyak
            'is_admin' => true,
            'is_active' => true,
        ]);

        // Log token transaction untuk admin
        TokenTransaction::create([
            'user_id' => $admin->id,
            'amount' => 9999,
            'type' => 'initial',
            'description' => 'Initial admin token allocation',
            'balance_after' => 9999,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Admin user created successfully!');
    }
}
