<?php

namespace App\Http\Controllers\Admin;

use App\Models\WlaCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminCompaniesController extends Controller
{
    public function __construct() {
        parent::__construct();
        $this->middleware('perms:admin-manage-companies');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $companies = WlaCompany::get();
        return view('admin.companies.index', [
            'companies' => $companies
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(WlaCompany $company)
    {
        return view('admin.companies.edit', [
            'company' => $company
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, WlaCompany $company)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);

        $company->name = $request->input('name');
        $company->description = $request->input('description');
        $company->url = $request->input('url');
        $company->domain = $request->input('domain');
        if($picture = $request->file('picture')) {
            $company->picture = $picture; 
        }
        $company->save();

        flash('Company has been updated', 'success');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
