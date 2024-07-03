<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Models\Task;
use App\Models\TaskAttempt;
use App\Models\TaskTime;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class Kernel extends ConsoleKernel
{


//TODO:: FIX THIS AND ADD CRON JOB IN THE SERVER

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->call(function () {
            Log::info("Morning tasks fetched for today:");
    
            $dayOfWeek = strtolower(Carbon::now()->englishDayOfWeek);
            Log::info("Day of the week: " . $dayOfWeek);
    
            $tasks = $this->getTasksForKernel($dayOfWeek);
            
            foreach ($tasks as $task) {
                Log::info("Task ID: " . $task);
                Log::info("Task ID: " . $task->id);
                Log::info("Task Name: " . $task->name);
                Log::info("Task Start Date: " . $task->start_date);
                Log::info("Task Time of Day: " . $task->time_of_day);
                // You can log other relevant task details here
    
                Log::info("Task marked as failed for today: " . $task->id);
                // Implement your logic to mark the task as failed here if needed
            }
        })->everyMinute(); // Adjust the time as per your requirement
    }

    public function getTasksForKernel($day)
    {
        $dayMap = [
            'sunday' => Carbon::SUNDAY,
            'monday' => Carbon::MONDAY,
            'tuesday' => Carbon::TUESDAY,
            'wednesday' => Carbon::WEDNESDAY,
            'thursday' => Carbon::THURSDAY,
            'friday' => Carbon::FRIDAY,
            'saturday' => Carbon::SATURDAY,
        ];
    
        // Validate the day parameter against dayMap keys
        if (!array_key_exists($day, $dayMap)) {
            throw new \InvalidArgumentException("Invalid day parameter: $day");
        }
    
        $today = Carbon::today();
        $targetDate = $today;
    
        // Check if today matches the given day
        if ($today->dayOfWeek !== $dayMap[$day]) {
            // Get the next occurrence of the given day
            $targetDate = $today->next($dayMap[$day]);
        }
    
        $targetDate = $targetDate->startOfDay();
    
        // Fetch tasks with their categories and times
        $tasks = Task::with(['taskCategory', 'taskTimes', 'taskAttempts'])->get()->filter(function ($task) use ($targetDate) {
            $startDate = Carbon::parse($task->start_date)->startOfDay();
    

            // TEST WHEN HOME :::::

            /*


$tasks = Task::with(['taskCategory', 'taskTimes', 'taskAttempts' => function ($query) use ($targetDate) {
    // Filter task attempts by today's date
    $query->whereDate('created_at', $targetDate->format('Y-m-d'));
}])->get()->filter(function ($task) use ($targetDate) {
    $startDate = Carbon::parse($task->start_date)->startOfDay();

*/



            // Check if repeat_interval is not null and greater than 0
            if ($task->repeat_interval !== null && $task->repeat_interval > 0) {
                return $startDate->diffInDays($targetDate) % $task->repeat_interval === 0;
            }
    
            // If repeat_interval is null or 0, treat it as a one-time task
            // Include the task if today matches the start_date
            return $startDate->isSameDay($targetDate);
        });
    
        // Transform tasks to include duplicates for multiple times
        $transformedTasks = [];
        Log::info($tasks . "     TASJKSSSSSSS");
        foreach ($tasks as $task) {
            Log::info(count($task->taskTimes) . "     TIMESS");
            $categoryName = $task->taskCategory->name;
            foreach ($task->taskTimes as $time) {
                $duplicatedTask = clone $task;
                $duplicatedTask->category_name = $categoryName; // Set category name
                $duplicatedTask->time_of_day = $time->time;
    
                $duplicatedTask->task_times = collect([$time])->map(function ($time) {
                    return [
                        'id' => $time->id,
                        'task_id' => $time->task_id,
                        'time' => $time->time,
                        // Add more fields as needed, excluding 'created_at' and 'updated_at'
                    ];
                });
    
                unset($duplicatedTask->taskCategory); // remove the relationship
                unset($duplicatedTask->taskTimes);
                $transformedTasks[] = $duplicatedTask;
            }
        }
    
        // Log the fetched tasks for debugging
        Log::info('Fetched tasks for ' . ucfirst($day) . ': ' . count($transformedTasks));
    
        $sortedTasks = $this->sortTasksByTimeOfDay($transformedTasks, 'morning');
    


        // CHECK IF COMPLETED?? 
  foreach ($sortedTasks as $task )
        {
            Log::info("Testing info?????????????? " . count($task->taskAttempts));
            
        }




        return $sortedTasks;
    }

    private function sortTasksByTimeOfDay($tasks, $timeOfDay = null)
    {
        $timeOrder = [
            'morning' => 1,
            'afternoon' => 2,
            'evening' => 3,
            'night' => 4,
        ];
    
        // Filter tasks based on $timeOfDay if provided
        if ($timeOfDay) {
            $filteredTasks = array_filter($tasks, function ($task) use ($timeOfDay) {
                return strtolower($task->time_of_day) === strtolower($timeOfDay);
            });
        } else {
            $filteredTasks = $tasks; // If no $timeOfDay provided, return all tasks
        }
    
        // Sort filtered tasks based on time of day order
        usort($filteredTasks, function ($task1, $task2) use ($timeOrder) {
            $time1 = array_search(strtolower($task1->time_of_day), array_keys($timeOrder));
            $time2 = array_search(strtolower($task2->time_of_day), array_keys($timeOrder));
    
            return $time1 <=> $time2;
        });
    
        return $filteredTasks;
    }
    



public function recordFailedTasks($taskId)
{
    // Validate the input data
  

    // Load the task along with its task_times relationship
    try {
        $task = Task::with('taskTimes')->findOrFail($taskId);
        Log::info('Loaded task with ID: ' . $task);
    } catch (\Exception $e) {
        Log::error('Task not found: ' . $e->getMessage());
        return response()->json(['error' => 'Task not found'], 404);
    }

    // Parse the attempt date from the request
    try {
        $attemptDate = Carbon::parse($validatedData['attempt_date'])->format('Y-m-d');
    } catch (\Exception $e) {
        Log::error('Invalid date format: ' . $e->getMessage());
        return response()->json(['error' => 'Invalid date format'], 400);
    }

    // Check if the task is already marked as completed on the same day
    $existingCompletedAttempts = TaskAttempt::where('task_id', $taskId)
                                            ->whereDate('attempt_date', $attemptDate)
                                            ->where('status', 'completed')
                                            ->count();

    // Check the number of task times associated with the task
    $taskTimesCount = $task->taskTimes->count();

    Log::info('Existing completed attempts: ' . $existingCompletedAttempts);
    Log::info('Task times count: ' . $taskTimesCount);

    if ($existingCompletedAttempts >= $taskTimesCount && $validatedData['status'] == 'completed') {
        Log::info('Task already completed for the maximum allowed times today.');
        return response()->json([
            'error' => 'This task has already been marked as completed for the maximum allowed times today.'
        ], 400);
    }

    // Create a new attempt
    try {
        $attempt = new TaskAttempt();
        $attempt->task_id = $task->id;
        $attempt->attempt_date = $validatedData['attempt_date'];
        $attempt->status = $validatedData['status'];
        $attempt->save();
    } catch (\Exception $e) {
        Log::error('Error saving attempt: ' . $e->getMessage());
        return response()->json(['error' => 'Error saving attempt'], 500);
    }

    return response()->json($attempt, 201);
}




}
