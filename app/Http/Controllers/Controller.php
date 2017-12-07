<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Contracts\Validation\Validator;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $mainScript = 'main';

    public function __construct() {
        view()->share('mainScript', $this->mainScript);

        $this->middleware(function ($request, $next) {
            return $next($request);
        });
    }

    // 
    protected function formatValidationErrors(Validator $validator)
    {
        $messages = $validator->errors()->getMessages();
        flash($messages, 'danger');
        return $messages;
    }
}
