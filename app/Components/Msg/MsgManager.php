<?php

namespace App\Components\Msg;

use \Carbon\Carbon;
use App\Models\User;
use Illuminate\Contracts\Events\Dispatcher;
use App\Components\Msg\Models\Conversation;

class MsgManager {

    protected $dispatcher;

    public function __construct(Dispatcher $dispatcher) {
        $this->dispatcher = $dispatcher;
    }

    public function createConversation($usersIds) {
        $users = User::whereIn('id', $usersIds)->get();

        if($users->count() > 1) {
            $conversation = Conversation::create();

            foreach($users as $user) {
                $conversation->addUser($user, true);
            }

            $this->dispatcher->fire('conversation.created', [$conversation]);

            return $conversation;
        } 
        return null;
    }

}