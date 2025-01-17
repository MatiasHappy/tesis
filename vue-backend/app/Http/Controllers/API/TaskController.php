<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskAttempt;
use App\Models\TaskTime;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class TaskController extends Controller
{


    // TODO::: RETURN AND HANDLE ERROR IF 




    public function index()
    {
        $tasks = Task::with('taskCategory')->get();

    $tasks->each(function ($task) {
        $task->category_name = $task->taskCategory->name;
        unset($task->taskCategory); // Optionally remove the relationship to avoid redundant data
    });
    }

    
    public function getTasksForDay($day)
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
    
        $today = Carbon::today();
        $targetDate = $today;
    
        // Check if today matches the given day
        if ($today->dayOfWeek !== $dayMap[$day]) {
            // Get the next occurrence of the given day
            $targetDate = $today->next($dayMap[$day]);
        }
    
        $targetDate = $targetDate->startOfDay();
    
        // Fetch tasks with their categories and times
        $tasks = Task::with(['taskCategory', 'taskTimes'])->get()->filter(function ($task) use ($targetDate) {
            $startDate = Carbon::parse($task->start_date)->startOfDay();
    
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
    
        foreach ($tasks as $task) {
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
        $sortedTasks = $this->sortTasksByTimeOfDay($transformedTasks);

    return response()->json($sortedTasks);
    
    }
    
 
    
    public function store(Request $request)
    {
        try {
            // Validate the request data
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'repeat_interval' => 'nullable|integer',
                'start_date' => 'required|date',
                'end_date' => 'nullable|date',
                'time_of_day' => 'required|array',
                'time_of_day.*' => 'in:morning,afternoon,evening,night',
                'task_category_id' => 'nullable|exists:task_categories,id',
            ]);
    
            // Extract time_of_day from validated data
            $timeOfDay = $validatedData['time_of_day'];
            unset($validatedData['time_of_day']);
    
            // Create the task without the time_of_day field
            $task = Task::create($validatedData);
    
            // Create the task times
            foreach ($timeOfDay as $time) {
                TaskTime::create([
                    'task_id' => $task->id,
                    'time' => $time,
                ]);
            }
    
            // Load the related task category if it exists
            if ($task->task_category_id) {
                $task->load('taskCategory');
                $task->category_name = $task->taskCategory->name;
                // Optionally remove the relationship to avoid redundant data
                unset($task->taskCategory);
            }
    
            // Return the created task with task times
            return response()->json($task->load('taskTimes'), 201);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Handle validation errors
            return response()->json([
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Log the error message
            \Log::error('Error creating task: ' . $e->getMessage());
            // Handle any other errors
            return response()->json([
                'error' => 'An error occurred while creating the task.'
            ], 500);
        }
    }
    
    
    

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $task->update($request->all());
        $task->load('taskCategory');
        $task->category_name = $task->taskCategory->name;
        unset($task->taskCategory); // Optionally remove the relationship to avoid redundant data
    
        return response()->json($task);
    }
    
    public function destroy($id)
    {
        Task::destroy($id);
        return response()->json(null, 204);
    }







    // Method to add times to a task
    public function addTimes(Request $request, $taskId)
    {
        $task = Task::findOrFail($taskId);
        $times = $request->input('times'); // Array of times

        foreach ($times as $time) {
            $taskTime = new TaskTime();
            $taskTime->task_id = $task->id;
            $taskTime->time = $time;
            $taskTime->save();
        }

        return response()->json(['message' => 'Times added successfully'], 201);
    }

    public function showById($id)
    {
        try {
            // Fetch the task along with its related models
            $task = Task::with(['taskCategory', 'taskTimes', 'taskAttempts'])->findOrFail($id);

            // Log the task details for debugging
            Log::info('Task fetched successfully', ['task' => $task]);

            // Ensure the related models are not null
            if (!$task->taskCategory) {
                throw new ModelNotFoundException('Task Category not found');
            }

            // Add category name to the task
            $task->category_name = $task->taskCategory->name;

            // Add task times and attempts
            $task->task_times = $task->taskTimes;
            $task->task_attempts = $task->taskAttempts;

            // Optionally remove the relationship data
            unset($task->taskCategory);
            unset($task->taskTimes);
            unset($task->taskAttempts);

            // Return the task as JSON
            return response()->json($task);
        } catch (ModelNotFoundException $e) {
            Log::error('Model not found', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'Task not found'], 404);
        } catch (\Exception $e) {
            Log::error('An error occurred', ['message' => $e->getMessage()]);
            return response()->json(['error' => 'An error occurred', 'message' => $e->getMessage()], 500);
        }
    }




// Function to sort tasks by time of day
private function sortTasksByTimeOfDay($tasks)
{
    usort($tasks, function ($task1, $task2) {
        $timeOrder = [
            'morning' => 1,
            'afternoon' => 2,
            'evening' => 3,
            'night' => 4,
        ];

        $time1 = array_search(strtolower($task1->time_of_day), array_keys($timeOrder));
        $time2 = array_search(strtolower($task2->time_of_day), array_keys($timeOrder));

        return $time1 <=> $time2;
    });

    return $tasks;
}





}
