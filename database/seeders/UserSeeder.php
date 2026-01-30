<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user (ID: 1)
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@roomie.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_admin' => true,
            'is_verified' => true,
        ]);

        // Create regular users
        User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_admin' => false,
            'is_verified' => true,
        ]);

        User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_admin' => false,
            'is_verified' => true,
        ]);

        User::create([
            'name' => 'Ahmed Hassan',
            'email' => 'ahmed@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_admin' => false,
            'is_verified' => true,
        ]);

        User::create([
            'name' => 'Sara Mohamed',
            'email' => 'sara@example.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_admin' => false,
            'is_verified' => true,
        ]);

        // Create some unverified users
        User::create([
            'name' => 'Mike Johnson',
            'email' => 'mike@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'is_verified' => false,
        ]);

        User::create([
            'name' => 'Lisa Brown',
            'email' => 'lisa@example.com',
            'password' => Hash::make('password'),
            'is_admin' => false,
            'is_verified' => false,
        ]);
    }
}
