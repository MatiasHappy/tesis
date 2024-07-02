<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'repeat_interval',
        'start_date',
        'end_date',
        'duration',
        'time_of_day',
        'task_category_id',
        'task_ended'

    ];

    
    public function taskCategory()
    {
        return $this->belongsTo(TaskCategory::class);
    }

    public function taskTimes()
    {
        return $this->hasMany(TaskTime::class);
    }

    // Define the relationship with TaskAttempt
    public function taskAttempts()
    {
        return $this->hasMany(TaskAttempt::class);
    }
}
