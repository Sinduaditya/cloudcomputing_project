<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // SystemSettingSeeder::class,
            // UserSeeder::class,
            AdminUserSeeder::class,
            DemoUserSeeder::class,
            // Tambahkan seeder lain disini jika diperlukan
        ]);
    }
}
