<?php
// app/Models/TaskMedia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskList extends Model
{
    protected $table = 'task_list';

    protected $fillable = [
        'task_id',
        'tasks',
        'assign_to',
        'assign_by',
        'status',
        'updated_by'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
