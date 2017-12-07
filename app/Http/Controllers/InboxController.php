<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Components\Msg\Models\Conversation;

class InboxController extends Controller
{
    public function inbox() 
    {
        $conversation = \Auth::user()->conversations()->first();
        return view('inbox.conversation', [
            'conversation' => $conversation,
            'title' => 'Inbox'
        ]);
    }

    public function compose($id = null) 
    {
        $user = is_null($id) || \Auth::id() == $id ? null : User::find($id);  
        return view('inbox.compose', [
            'user' => $user,
            'title' => 'Compose'
        ]);
    } 

    public function send(Request $request) {
        $this->validate($request, [
            'recipient' => 'required',
            'message' => 'required'
        ]);

        $recipient = User::find($request->input('recipient'));
        if(is_null($recipient)) {
            flash('Recipient not found', 'danger');
            return redirect()->back();
        }

        $conversation = \Msg::createConversation([\Auth::id(), $recipient->id]);
        $conversation->addMessage($request->input('message'));

        return redirect()->route('inbox.conversation', $conversation->id);
    }

    public function reply(Request $request) 
    {
        $this->validate($request, [
            'convId' => 'required|exists:msg_conversations,id',
            'message' => 'required'
        ]);

        $conversation = Conversation::findOrFail($request->input('convId'));
        $conversation->addMessage($request->input('message'));

        return redirect()->route('inbox.conversation', $conversation->id);
    }

    public function conversation($id) {
        $conversation = Conversation::findOrFail($id);
        if(!$conversation->canViewByUser()) {
            abort(404);
        }
        $conversation->doRead();
        return view('inbox.conversation', [
            'conversation' => $conversation,
            'title' => 'Inbox'
        ]);
    }
}
