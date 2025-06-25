<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TelegramWebhookMessage extends Model
{
    use HasFactory;

    protected $table = 'telegram_webhook_messages';

    protected $fillable = [
        'chat_id', 'message_id', 'name', 'username', 'message_text', 
        'reply_flag', 'pinned_flag', 'reply_chat_id', 'reply_message_id', 
        'reply_username', 'reply_name', 'reply_message_text', 'message_array_data', 
        'messageDate', 'media_type','media_file_id','created_at', 'is_deleted'
    ];
}
