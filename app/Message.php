<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $table = 'messages';
    protected $fillable = ['chat_id', 'sender_id', 'message', 'type', 'attachment_url', 'reply_to'];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }

}
