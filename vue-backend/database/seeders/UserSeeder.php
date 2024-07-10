<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Household;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $household = Household::create([
            'name' => 'Smith Family',
        ]);
        // Create a specific user
        $user = User::create([
            'name' => 'mats',
            'email' => 'mats@mats.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Use a secure password
            'remember_token' => Str::random(10),
            'date_of_birth' => Carbon::parse('1999-05-02'),
            'gender' => 'male',
            'household_id' => 1,
        ]);

        $user = User::create([
            'name' => 'mats2',
            'email' => 'mats2@mats2.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Use a secure password
            'remember_token' => Str::random(10),
            'date_of_birth' => Carbon::parse('1990-02-05'),
            'gender' => 'male',
            'household_id' => 1,
        ]);

        $user = User::create([
            'name' => 'mats',
            'email' => 'mats3@mats3.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Use a secure password
            'remember_token' => Str::random(10),
            'date_of_birth' => Carbon::parse('1990-01-01'),
            'gender' => 'male',
            'household_id' => 1,
        ]);

        $user = User::create([
            'name' => 'zai',
            'email' => 'zai@zai.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'), // Use a secure password
            'remember_token' => Str::random(10),
            'date_of_birth' => Carbon::parse('1990-01-01'),
            'gender' => 'male',
            'household_id' => 1,
        ]);
        // Create additional fake users
    
    }
}
