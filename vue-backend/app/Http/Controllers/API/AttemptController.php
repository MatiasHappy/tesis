<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Task;
use App\Models\TaskAttempt;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class AttemptController extends Controller
{
    public function recordAttempt(Request $request, $taskId)
    {
        // Validate the input data
        try {
            $validatedData = $request->validate([
                'attempt_date' => 'required|date',
                'status' => 'required|in:completed, failed',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed: ' . json_encode($e->errors()));
            return response()->json(['error' => 'Validation failed', 'details' => $e->errors()], 400);
        }

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
