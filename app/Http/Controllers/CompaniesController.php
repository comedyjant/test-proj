<?php

namespace App\Http\Controllers;

use App\Models\WlaCompany;
use Illuminate\Http\Request;

class CompaniesController extends Controller
{
    
    public function index($companySlug) {
        $company = WlaCompany::where('slug', $companySlug)->firstOrFail();
        return view('companies.company', [
            'company' => $company
        ]);
    }

    public function apiTypeaheadSearch(Request $request) {
        $query = preg_replace('!\s+!', ' ', $request->get('query'));        
        $companies = WlaCompany::select('name', 'slug')
            ->where('status', 1)
            ->where('name', 'like', $query.'%')
            ->limit(5)
            ->get()            
            ->toArray();
        return response()->json($companies);
    }

}
