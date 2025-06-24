<?php
// app/Models/TaskComment.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskComment extends Model
{
    protected $fillable = [
        'task_id',
        'comment',
        'added_by'
    ];
}
