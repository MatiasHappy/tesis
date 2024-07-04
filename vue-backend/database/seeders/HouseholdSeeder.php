<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Household;
use App\Models\User;

class HouseholdSeeder extends Seeder
{
    public function run()
    {
        // Create households
        $households = [
            ['name' => 'The Mansion'],
            ['name' => 'Household2'],
           
        ];

        foreach ($households as $householdData) {
            $household = Household::create($householdData);

            // Assign users to the household
            $users = User::inRandomOrder()->take(5)->pluck('id');
            $household->users()->attach($users);
        }
    }
}
