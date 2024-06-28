<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\TaskAttempt;
use App\Models\Task;
use Carbon\Carbon;

class AttemptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch the first task
        $task = Task::firstOrFail();

        // Number of attempts you want to create for this task
        $numberOfAttempts = 10;

        // Creating multiple attempts for the same task
        for ($i = 1; $i <= $numberOfAttempts; $i++) {
            TaskAttempt::create([
                'task_id' => $task->id,
                'attempt_date' => Carbon::now()->subDays($i)->toDateString(),
                'status' => 'failed',
            ]);
        }
    }
}
