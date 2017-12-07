<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Models\Content;
use App\Http\Controllers\Controller;

class AdminPagesController extends Controller
{
    private $pages = [        
        'about_us' => 'About Us',
        'where_we_are' => 'Where We Are',
        'terms_of_use' => 'Terms of Use',
        'privacy_policy' => 'Privacy Policy',
    ];

    public function index()
    {
        $pagesContent = collect($this->pages)->map(function($item, $key) {
            return settings($key);
        });
        $contentSelectList = Content::getSelectList();
        return view('admin.pages.index', [
            'pages' => $this->pages,
            'pagesContent' => $pagesContent,
            'contentSelectList' => $contentSelectList
        ]);
    }

    public function update(Request $request)
    {
        foreach($this->pages as $key => $page) {
            if($request->has($key)) {
                \Settings::set($key, $request->input($key));
            }            
        }
        return redirect()->route('admin.pages.index');
    }

}
