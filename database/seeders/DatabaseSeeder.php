<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'worker']);
        Role::create(['name' => 'user']);
        // create user
        User::create([
            'name' => config('app.admin.name'),
            'email' => config('app.admin.email'),
            'password' => Hash::make(config('app.admin.password')),
        ])->assignRole('admin');
        if (!app()->isProduction())
            $this->call(CreateTestDataSeeder::class);
    }
}
