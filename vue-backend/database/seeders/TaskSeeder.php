<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\TaskCategory;
use App\Models\TaskTime;
use App\Models\User;
use App\Models\Household;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run()
    {
        // Sample categories
        $categories = [
            ['name' => 'Duty'],
            ['name' => 'Habit'],
            ['name' => 'Fun']
        ];

        foreach ($categories as $category) {
            TaskCategory::create($category);
        }

        // Sample tasks
        $tasks = [
            [
                'name' => 'STOP THE MANDESS !!',
                'task_category_id' => 1, // Duty
                'repeat_interval' => 1, // every 1 day
                'start_date' => Carbon::now()->toDateString(), // started today
                'task_times' => ['morning', 'afternoon']
            ],
            [
                'name' => 'Test End',
                'task_category_id' => 2, // Habit
                'repeat_interval' => 1, // every day
                'start_date' => Carbon::now()->subDays(1)->toDateString(),
                'end_date' => Carbon::now()->addDays(2)->toDateString(), // ends in 2 days
                'task_times' => ['morning']
            ],
            [
                'name' => 'Read a book',
                'task_category_id' => 3, // Fun
                'repeat_interval' => 3, // every 3 days
                'start_date' => Carbon::now()->subDays(2)->toDateString(), // started 2 days ago
                'task_times' => ['evening']
            ],
            [
                'name' => 'Grocery shopping',
                'task_category_id' => 1, // Duty
                'repeat_interval' => 2, // every 7 days
                'start_date' => Carbon::now()->subDays(6)->toDateString(),
                'end_date' => Carbon::now()->subDays(3)->toDateString(), // started 6 days ago
                'task_times' => ['afternoon']
            ],
            [
                'name' => 'Meditate',
                'task_category_id' => 2, // Habit
                'repeat_interval' => 1, // every day
                'start_date' => Carbon::now()->toDateString(),
                'end_date' => Carbon::now()->addDays(2)->toDateString(), // starts today
                'task_times' => ['morning', 'night']
            ],
        ];

        $household = Household::first(); // Assume you want to assign the first household to all tasks

        foreach ($tasks as $taskData) {
            $taskTimes = $taskData['task_times'];
            unset($taskData['task_times']);
            
            // Assign a random user as the creator
            $taskData['created_by'] = User::inRandomOrder()->first()->id;
            $taskData['household_id'] = $household->id;

            $task = Task::create($taskData);

            foreach ($taskTimes as $time) {
                TaskTime::create([
                    'task_id' => $task->id,
                    'time' => $time,
                ]);
            }
        }
    }
}

