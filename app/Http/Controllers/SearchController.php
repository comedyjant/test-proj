<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Models\User;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function apiSearch(Request $request) {
        $users = User::search($request->input('search'))
            ->take(5)
            ->get()
            ->map(function($item) {
                return ['name' => $item->getFullName(),
                        'url' => route('user.profile', $item->slug)];
            })->toArray();

        return response()->json($users);
    }
}
