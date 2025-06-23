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

}
