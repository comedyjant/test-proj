<?php

namespace App\Components\Msg\Models;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{

    protected function getUser($user = null){
        if(!$user)  $user = \Auth::user();
        if(!$user){
            throw new \Exception('It is not possible to add a message without a valid user or login.');
        }
        return $user;
    }
}