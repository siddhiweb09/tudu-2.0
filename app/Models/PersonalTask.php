<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class PersonalTask extends Model
{
    use HasFactory;
    protected $fillable = [
        'task_id',
        'title',
        'description',
        'priority',
        'is_recurring',
        'frequency',
        'frequency_duration',
        'reminders',
        'assign_by',
        'status',
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
        $prefix = 'PERTASK-';
        $datePart = now()->format('Ymd');
        $randomPart = Str::upper(Str::random(4));

        do {
            $taskId = $prefix . $datePart . '-' . $randomPart;
            $randomPart = Str::upper(Str::random(4));
        } while (self::where('task_id', $taskId)->exists());

        return $taskId;
    }

    // public function items()
    // {
    //     return $this->hasMany(TaskItem::class);
    // }

    public function media()
    {
        return $this->hasMany(TaskMedia::class);
    }
}
