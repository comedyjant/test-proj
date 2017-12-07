<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\WlaCompany;
use Illuminate\Http\Request;
use App\Models\WlaTeamMember;
use App\Models\WlaCompanyTeam;

class CompanyTeamsController extends Controller
{
    protected $mainScript = 'company';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($companySlug)
    {
        $company = WlaCompany::where('slug', $companySlug)->firstOrFail();
        return view('companies.teams', [
            'company' => $company
        ]);
    }

    public function updateMemberTeam(Request $request, $companySlug, User $user) {
        $company = WlaCompany::where('slug', $companySlug)->firstOrFail();
        $teamId = $request->input('team_id');

        if($teamId == 0 || $company->teams()->where('id', $teamId)->exists()) {
            $response = $company->deleteUserFromTeams($user);

            $team = WlaCompanyTeam::find($teamId);
            if($team) {
                $team->users()->attach($user);
            }            
            return response()->json(['status'  => 'success']);    
        }

        return response()->json(['status' => 'error']);        
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
    public function store(Request $request, $companySlug)
    {
        $this->validate($request, [
            'name' => 'required'
        ]);
        $company = WlaCompany::where('slug', $companySlug)->firstOrFail();
        $company->teams()->create([
            'name' => $request->input('name')
        ]);
        flash('New company has been created', 'success');
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
    public function destroy($companySlug, $id)
    {
        $company = WlaCompany::where('slug', $companySlug)->firstOrFail();
        $company->teams()->where('id', $id)->delete();
        flash('Company team has been deleted', 'success');
        return redirect()->back();
    }
}
