<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\TaskCategory;
use Illuminate\Http\Request;

class TaskCategoryController extends Controller
{
    public function index()
    {
        return response()->json(TaskCategory::all());
    }
}
