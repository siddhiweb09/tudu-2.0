<?php
// app/Models/Task.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Task extends Model
{
    protected $fillable = [
        'task_id',
        'title',
        'description',
        'project_name',
        'task_list',
        'department',
        'priority',
        'is_recurring',
        'frequency',
        'frequency_duration',
        'reminders',
        'links',
        'assign_to',
        'assign_by',
        'status',
        'final_status',
        'remarks',
        'ratings',
        'due_date'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            $task->task_id = self::generateTaskId();
        });
    }

    public static function generateTaskId()
    {
        $prefix = 'TASK-';
        $datePart = now()->format('Ymd');
        $randomPart = Str::upper(Str::random(4));

        do {
            $taskId = $prefix . $datePart . '-' . $randomPart;
            $randomPart = Str::upper(Str::random(4));
        } while (self::where('task_id', $taskId)->exists());

        return $taskId;
    }

    // public function logs()
    // {
    //     return $this->hasMany(TaskLog::class);
    // }
}
