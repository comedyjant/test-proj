<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\WlaCompany;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminCompanyMembersController extends Controller
{
    protected $mainScript = 'adminCompany';

    public function __construct() {
        parent::__construct();
        $this->middleware('perms:admin-manage-companies');
    }

    public function index(WlaCompany $company)
    {
        return view('admin.companies.members', [
            'company' => $company
        ]);
    }

    public function updateAdminStatus(WlaCompany $company, User $user, Request $request) {
        if(! $company->users()->where('user_id', $user->id)->exists()) {
            abort(403);
        }
        $company->users()->updateExistingPivot($user->id, ['admin' => $request->input('status') == 'true']);
        return response()->json(['status' => 'success']);
    }

}
