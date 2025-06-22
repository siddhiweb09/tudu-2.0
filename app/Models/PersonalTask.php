<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalTask extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'category',
        'due_date',
        'priority',
        'status',
        'time_estimate',
        'is_habit',
        'habit_frequency',
        'notes',
        'okr'
    ];

    protected $casts = [
        'due_date' => 'datetime',
        'is_habit' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(TaskDocument::class);
    }

    public function timeLogs()
    {
        return $this->hasMany(TaskTimeLog::class);
    }

    public function totalTimeSpent()
    {
        return $this->timeLogs()->sum('duration');
    }
}