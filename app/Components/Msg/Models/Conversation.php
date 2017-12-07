<?php

namespace App\Components\Msg\Models;

use Auth;
use App\Models\User;

class Conversation extends BaseModel
{
    protected $table = 'msg_conversations';
    
    public function messages()
    {
        return $this->hasMany(Message::class, 'conv_id')->with('user');
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'msg_users', 'conv_id', 'user_id');
    }

    public function otherUsers(User $user = null)
    {
        $user = $this->getUser($user);
        return $this->users()->where('user_id', '!=', $user->id)->get();            
    }    

    public function addMessage($content, User $user = null) 
    {
        $user = $this->getUser($user);

        if(! $this->canViewByUser($user)) {
            return null;
        }

        $message = Message::create([
            'user_id' => \Auth::id(),
            'conv_id' => $this->id,
            'content' => $content
        ]);

        foreach($this->users as $convUser) {
            $state = ($user->id == $convUser->id) ? State::READ : State::UNREAD;
            $message->setState($convUser, $state);
        }

        if (isset(static::$dispatcher)) {
            static::$dispatcher->fire('message.created', [$message]);
        }

        return $message;
    }

    public function addUser(User $user, $force = false){
        // Exception if you are not conversation member
        if(!$force && !$this->canViewByUser()) {
            throw new \Exception('You can\'t add user to this conversation');
        }

        // Add new user to the conversation only if he is not added yet
        if(!$this->canViewByUser($user)) {
            $this->users()->attach($user->id);
            foreach($this->messages as $message) {
                $message->setState($user, State::UNREAD);
            }
            $this->touch();
        }        
    }

    public function canViewByUser(User $user = null)
    {
        $user = $this->getUser($user);
        return $this->users()->where('user_id', $user->id)->exists(); 
    }

    public function latestMessage(){
        return $this->messages()->orderBy('created_at', 'desc')->first();
    }

    public function unreadMessages(){
        return $this->messages
                ->filter(function($item) {
                    return $item->isUnread();
                });
    }

    public function isUnread(){
        return $this->unreadMessages()->count() > 0;
    }

    public function doRead(){
        foreach($this->unreadMessages() as $unreadMessage){
            $unreadMessage->doRead();
        }
        return true;
    }

}
