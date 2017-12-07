<?php

namespace App\Http\Controllers\Account;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Components\WlaCompany as WlaCompanyService;

class ProfileSettingsController extends Controller
{    
    protected $mainScript = 'account';

    public function account() {        
        return view('user.settings.account', [
            'user' => \Auth::user()
        ]);
    }

    public function accountUpdate(Request $request) {
        $user = \Auth::user();

        $this->validate($request, [
            'first_name' => 'required|min:3',
            'last_name' => 'required|min:3',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'password' => 'confirmed|min:7'
        ]);

        $user->first_name = $request->input('first_name');
        $user->last_name = $request->input('last_name');
        $user->about = $request->input('about');

        if($user->email != trim($request->input('email'))) {
            $user->email = trim($request->input('email'));
            $user->confirmed = false;    
        }

        if($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        if($picture = $request->file('picture')) {
            $user->picture = $picture; 
        }
        $user->job = $request->input('job');
        $user->phone = $request->input('phone');
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

        flash('Your account has been updated', 'success');
        return redirect()->back();
    }

    public function wlaChanel() {
        $wlaChanel = \Auth::user()->wlaChanel;
        if(is_null($wlaChanel)) {
            abort(404);
        }

        return view('user.settings.wlaChanel', [
            'wlaChanel' => $wlaChanel
        ]);
    }

    public function updateWlaChanel(Request $request) { 
        $wlaChanel = \Auth::user()->wlaChanel;
        if(is_null($wlaChanel)) {
            abort(404);
        }

        $this->validate($request, [
            'title' => 'required',
            'slug' => 'unique:wla_chanels,slug,'.$wlaChanel->id
        ], [
            'slug.unique' => 'Url Name has already been taken.' 
        ]);   

        $wlaChanel->title = $request->input('title');     
        $wlaChanel->description = $request->input('description');
        $wlaChanel->slug = $request->input('slug');

        $wlaChanel->status = $request->has('status');
        $wlaChanel->public = $request->has('public');
        if($picture = $request->file('picture')) {
            $wlaChanel->picture = $picture; 
        }
        $wlaChanel->save();
        return redirect()->back();
    }


}
