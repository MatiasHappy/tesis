<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskTime extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'task_id',
        'time'
    ];
    // Define the relationship with Task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
