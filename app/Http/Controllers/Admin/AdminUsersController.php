<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Role;
use App\Http\Requests;
use App\Models\WlaChanel;
use Illuminate\Http\Request;
use App\Components\WlaCompany as WlaCompanyService;

class AdminUsersController extends AdminController
{
    protected $mainScript = 'adminUser';

    public function __construct() {
        parent::__construct();
        $this->middleware('perms:admin-manage-users');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $users = User::paginate(50);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        $roles = Role::get();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if($user->id == 1 && \Auth::id() != 1) {
            flash("You can't edit this user", 'danger');
            return redirect()->back();
        }
        
        $this->validate($request, [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'min:7'
        ]);

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');

        if($user->email != trim($request->input('email'))) {
            $user->email = trim($request->input('email'));
        }

        if($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        if($picture = $request->file('picture')) {
            $user->picture = $picture; 
        }
        $user->save();

        if(!$request->has('company')) {
            $user->companies()->detach();
        } else {
            $company = WlaCompanyService::getCompany($request->only('company', 'company-slug'));
            if($company && (is_null($user->company) || $user->company->id != $company->id)) {
                $user->companies()->detach();
                $user->companies()->attach($company);
            }
        }        
    
        if($request->has('roles') && $roles = Role::find($request->input('roles'))) {
            $user->roles()->sync($roles);
        } else {
            $user->roles()->detach();
        }

        flash('User details have been updated', 'success');
        return redirect()->back();        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->id == 1 && \Auth::id() != 1) {
            flash("You can't delete this user", 'danger');
            return redirect()->back();
        }

        if($user->id == \Auth::id()) {
            flash("You can't delete yourself", 'danger');
            return redirect()->back();
        }

        $user->delete();
        flash('User has been deleted', 'success');
        return redirect()->route('users.index');
    }
}
