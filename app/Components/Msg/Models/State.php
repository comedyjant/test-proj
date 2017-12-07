<?php

namespace App\Components\Msg\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class State extends Model
{
    const DELETED = 0;
    const UNREAD = 1;
    const READ = 2;
    const ARCHIVED = 3;
    
    protected $table = 'msg_states';

    protected $fillable = ['message_id', 'user_id', 'state'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function message() {
        return $this->belongsTo(Message::class, 'message_id');   
    }
}