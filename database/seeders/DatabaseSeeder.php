<?php

namespace Database\Seeders;


use Illuminate\Database\Seeder;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SettingSeeder::class);
        $this->call(RuleWithPermissionSeeder::class);
        $this->call(CreateAdminSeeder::class);
        if (!app()->isProduction())
            $this->call(CreateTestDataSeeder::class);
    }
}
