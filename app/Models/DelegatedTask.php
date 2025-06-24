<?php
// app/Models/Task.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DelegatedTask extends Model
{

    protected $table = "delegated_tasks";
    protected $fillable = [
        'task_id',
        'delegate_task_id',
        'title',
        'description',
        'department',
        'task_list',
        'department',
        'priority',
        'frequency',
        'frequency_duration',
        'reminders',
        'assign_to',
        'assign_by',
        'status',
        'final_status',
        'remarks',
        'ratings',
        'due_date',
        'visible_to'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($task) {
            $task->delegate_task_id = self::generateDelegateTaskId();
        });
    }

    public static function generateDelegateTaskId()
    {
        $prefix = 'DELTASK-';
        $datePart = now()->format('Ymd');
        $randomPart = Str::upper(Str::random(4));

        do {
            $delTaskId = $prefix . $datePart . '-' . $randomPart;
            $randomPart = Str::upper(Str::random(4));
        } while (self::where('delegate_task_id', $delTaskId)->exists());

        return $delTaskId;
    }

}
