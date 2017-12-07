<?php 

namespace App\Models;

use Baum\Node;
use App\Models\User;

class Comment extends Node{

    protected $table = 'comments';

    protected $scoped = ['commentable_id', 'commentable_type'];
    
    public function commentable(){
        return $this->morphTo();
    }

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function repliedComment(){
        return $this->belongsTo( \App\Models\Comment::class, 'replied_id');
    }
    
}