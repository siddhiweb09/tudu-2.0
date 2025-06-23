<?php
// app/Models/TaskLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    protected $fillable = [
        'task_id',
        'log_description',
        'added_by'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
