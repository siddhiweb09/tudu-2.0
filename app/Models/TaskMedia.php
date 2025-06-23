<?php
// app/Models/TaskMedia.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskMedia extends Model
{
    protected $table = 'task_medias';

    protected $fillable = [
        'task_id',
        'category',
        'file_name',
        'created_by'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
