<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Chat extends Model
{
    protected $table = 'chats';
    protected $fillable = ['last_message'];
    protected $dates = ['last_message_at'];

    public function participants()
    {
        return $this->belongsToMany(User::class, 'participants');
    }

    public function getMessagesAttribute()
    {
        return Message::leftJoin('deleted_messages', 'messages.id', 'deleted_messages.message_id')
            ->where('deleted_messages.user_id', '!=', 1)
            ->OrwhereNull('deleted_messages.message_id')
            ->where('chat_id', $this->id)
            ->get();
    }
}
