<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskSchedule extends Model
{
    protected $fillable = ['task_id', 'assigned_date', 'status','completion_date', 'frequency', 'created_by', 'updated_by'];
}
