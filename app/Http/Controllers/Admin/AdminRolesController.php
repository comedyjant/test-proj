<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Http\Requests;
use App\Models\Permission;
use Illuminate\Http\Request;

class AdminRolesController extends AdminController
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
        $roles = Role::get();
        return view('admin.roles.index', compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.roles.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|alpha_dash|unique:roles,name',
            'display_name' => 'required'
        ]);

        Role::create($request->all());
        flash('New role has been created', 'success');

        return redirect()->route('roles.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Role $role)
    {
        $permissions = Permission::getAll();
        
        $rolePermissions = $role->perms
                        ->map(function($item) {
                            return $item->id;
                        })
                        ->toArray();
        return view('admin.roles.show', compact('role', 'permissions', 'rolePermissions'));
    }

    public function updatePermissions(Request $request, Role $role) {
        $permissions = Permission::find($request->input('permissions'));
        $role->savePermissions($permissions);

        flash('Role permissions have been updated', 'success');
        return redirect()->route('roles.show', [$role->id]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Role $role)
    {
        return view('admin.roles.edit', compact('role'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Role $role)
    {
        $this->validate($request, [
            'name' => 'required|alpha_dash|not_in:superadmin|unique:roles,name,'.$role->id,
            'display_name' => 'required'
        ], [
            'name.not_in' => 'You cannot edit this role'
        ]);
        $role->update($request->all());
        flash('Role has been updated', 'success');
        return redirect()->route('roles.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Role $role)
    {
        if($role->name == 'superadmin') {            
            flash('You cannot delete this role', 'danger');
        } else {
            $role->delete();
            flash('Role has been deleted', 'success');
        }        
        return redirect()->route('roles.index');
    }
}
