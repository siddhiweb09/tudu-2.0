<?php
// app/Models/TaskLog.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TelegramWhatsappLog extends Model
{
    protected $fillable = [
        'task_id',
        'log_description',
        'notification_on'
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
