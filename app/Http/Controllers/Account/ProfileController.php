<?php

namespace App\Http\Controllers\Account;

use App\Models\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    public function profile ($slug) {
        $user = User::where('slug', $slug)->firstOrFail();
        return view('user.profile', compact('user'));
    }
}
