<?php

namespace App\Components\Msg\Traits;

use App\Components\Msg\Models\Message;
use App\Components\Msg\Models\Conversation;
use App\Components\Msg\Models\State as MessageState;

trait MsgTrait {

    public function conversations() {
        return $this->belongsToMany(Conversation::class, 'msg_users', 'user_id', 'conv_id')->with('messages')->orderBy('updated_at', 'desc');
    }

    public function messageStates() {
        return $this->hasMany(MessageState::class, 'user_id')->orderBy('updated_at', 'desc');
    }

    public function unreadMessageStates(){
        return $this->messageStates()->where('state', '=', MessageState::UNREAD)->orderBy('updated_at', 'desc')->get();
    }

    public function unreadMessagesCount(){
        return count($this->unreadMessageStates());
    }

    public function hasUnreadMessages(){
        return $this->unreadMessagesCount() > 0;
    }

    public function unreadConversations(){
        $unreadConversations = array();
        foreach($this->unreadMessages() as $unreadMessage){
            $unreadConversation = $unreadMessage->conversation;
            $conversationId = $unreadConversation->id;
             
            if(isset($unreadConversations[$conversationId])) continue;
             
            $unreadConversations[$conversationId] = $unreadConversation;
        }
        return collect($unreadConversations);         
    }

    public function unreadConversationsCount(){
        return $this->unreadConversations()->count();
    }


    public function unreadMessages(){   
        return $this->findMessages(MessageState::UNREAD);
    }

    public function findMessages($state = null)
    {
        $userId = $this->id;         
        $unreadMessages = Message::whereHas('messageStates', function($q) use( $userId, $state)
        {
            $q->where('user_id', $userId);            
            if($state) {
                $q->where('state', $state);
            }             
        })->with('conversation')->get();
         
        return $unreadMessages;
    }

}