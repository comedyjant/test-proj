<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminTrashController extends Controller
{
    public function __construct() {
        $this->middleware('perms:admin-manage-deleted-content');
    }

    public function index() {
        $data = [
            'users' => User::onlyTrashed()->count()
        ];
        return view('admin.trash.index', compact('data'));
    }

    public function getUsers() { 
        return $this->trashItems(User::class, 'trash.users', function($item) {
                        $item->title = $item->getFullName();
                        return $item;
                    });
    }

    public function postUsers(Request $request) {
        $this->trashAction(User::class, $request);
        return redirect()->route('trash.users');
    }

    private function trashAction($class, Request $request) {
        $this->validate($request, [
            'id' => 'required',
            'action' => 'required|in:1,2'
        ]);
        $item = $class::onlyTrashed()->where('id', $request->input('id'))->firstOrFail();
        switch ($request->input('action')) {
            case '1':
                $item->restore();
                flash('Item has been restored', 'success');
                break;
            
            case '2':
                $item->forceDelete();
                flash('Item has been deleted', 'success');
                break;
        }
    }

    private function trashItems($class, $routeName, \Closure $func = null) {
        $items = $class::onlyTrashed()->paginate(50);
        $links = $items->links();
        if(!is_null($func)) {
            $items = $items->map($func);    
        }       

        return view('admin.trash.show', [
            'items' => $items,
            'links' => $links,
            'routeName' => $routeName
        ]);
    }
}
