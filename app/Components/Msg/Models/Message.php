<?php

namespace App\Components\Msg\Models;

use App\Models\User;

class Message extends BaseModel
{
    protected $table = 'msg_messages';

    protected $fillable = ['conv_id', 'user_id', 'content'];

    protected $touches = ['conversation'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function conversation() 
    {
        return $this->belongsTo(Conversation::class, 'conv_id');
    }

    public function messageStates()
    {
        return $this->hasMany(State::class, 'message_id');
    }

    public function messageState(User $user = null)
    {
        $user = $this->getUser($user);
        $state = $this->messageStates()->where('user_id', '=', $user->id)->first();
        if(is_null($state)) {
            $state = $this->setState($user, State::UNREAD);
        }
        return $state;
    }

    public function setState(User $user = null, $newState = State::READ) 
    {
        $user = $this->getUser($user);
        $state = State::firstOrNew(['user_id' => $user->id, 'message_id' => $this->id]);
        $state->state = $newState;
        $state->save();
        return $state;
    }

    public function doRead(User $user = null) {
        $user = $this->getUser($user);
        return $this->setState($user, State::READ);
    }

    public function doDelete(User $user = null) {
        $user = $this->getUser($user);
        return $this->setState($user, State::DELETED);
    }

    public function isRead(User $user = null) {
        $user = $this->getUser($user);
        $state = $this->messageState($user);
        return $state->state == State::READ;
    }

    public function isUnread(User $user = null) {
        $user = $this->getUser($user);
        $state = $this->messageState($user);       
        return $state->state == State::UNREAD;       
    }

    public function isDeleted(User $user = null) {
        $user = $this->getUser($user);
        $state = $this->messageState($user);
        return $state->state == State::DELETED;
    }
}