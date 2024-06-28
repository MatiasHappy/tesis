<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskAttempt extends Model
{
    use HasFactory;
  
    
    protected $fillable = [
        'task_id',
        'attempt_date',
        'status'
    ];
    
    // Define the relationship with Task
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
