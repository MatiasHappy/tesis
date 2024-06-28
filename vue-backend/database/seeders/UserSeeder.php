<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create a specific user
        User::create([
            'name' => 'mats',
            'email' => 'mats@mats.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Use a secure password
            'remember_token' => Str::random(10),
        ]);

        // Create additional fake users
        //User::factory()->count(10)->create();
    }
}
