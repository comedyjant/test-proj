<?php

namespace App\Http\Controllers\Admin;

use App\Models\WlaCompany;
use Illuminate\Http\Request;
use App\Models\WlaCompanyTeam;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;

class AdminCompanyTeamsController extends Controller
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
    public function index(WlaCompany $company)
    {
        return view('admin.companies.teams.index', [
            'company' => $company            
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, WlaCompany $company)
    {
        $this->validate($request, [
            'name' => [
                'required',
                Rule::unique('wla_company_teams', 'name')->where(function($query) use ($company){
                    $query->where('company_id', $company->id);
                })
            ]
        ]);

        $company->teams()->create([
            'name' => $request->name
        ]);

        flash('New team has been created', 'success');
        return redirect()->back();        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
