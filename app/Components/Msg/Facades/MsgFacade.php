<?php

namespace App\Components\Msg\Facades;

use Illuminate\Support\Facades\Facade;

class MsgFacade extends Facade{

    protected static function getFacadeAccessor() { 
        return 'messaging'; 
    }

} 