<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Http\Requests;
use App\Models\Permission;
use Illuminate\Http\Request;

class AdminPermissionsController extends AdminController
{

    public function __construct() {
        $this->middleware('perms:admin-manage-roles-permissions');
    }
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::getAll();
        return view('admin.roles.permissions.index', compact('permissions'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Permission $permission)
    {
        $permissionRoles = $permission->roles
                        ->map(function($item) {
                            return $item->id;
                        })
                        ->toArray();
        $roles = Role::get();
        return view('admin.roles.permissions.edit', compact('permission', 'roles', 'permissionRoles'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Permission $permission)
    {
        $checkedRoles = $request->input('roles');

        Role::get()
            ->each(function($role) use ($permission, $checkedRoles) {
                if(!is_null($checkedRoles) && in_array($role->id, $checkedRoles)) {
                    if(! $role->hasPermission($permission->name)) {
                        $role->attachPermission($permission);
                    }
                } else {
                    $role->detachPermission($permission);
                }       
            });       

        flash('Permission roles have been updated', 'success');
        return redirect()->route('permissions.edit', [$permission->id]);
    }

}
