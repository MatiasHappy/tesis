<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TaskCategory;

class TaskCategorySeeder extends Seeder
{
    public function run()
    {
        $categories = ['duty', 'habit', 'fun'];

        foreach ($categories as $category) {
            TaskCategory::create(['name' => $category]);
        }
    }
}
