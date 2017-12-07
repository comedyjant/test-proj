<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Models\Content;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminContentController extends Controller
{
    protected $mainScript = 'adminContent';

    public function __construct() {
        parent::__construct();
        $this->middleware(['perms:admin-manage-content']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $pages = Content::get();
        return view('admin.content.index', [
            'pages' => $pages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.content.create');
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
            'title' => 'required',
            'body' => 'required'
        ]);

        $content = Content::create([
            'user_id' => \Auth::id(),
            'title' => $request->input('title'),
            'body' => $request->input('body')
        ]);

        flash('New content has been created', 'success');
        return redirect()->route('admin.content.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $content = Content::findOrFail($id);
        return view('admin.content.edit', [
            'content' => $content
        ]);
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
        $this->validate($request, [
            'title' => 'required',
            'body' => 'required'
        ]);

        $content = Content::findOrFail($id);
        $content->update([
            'title' => $request->input('title'),
            'body' => $request->input('body')
        ]);
        flash('Content has been updated', 'success');
        return redirect()->route('admin.content.edit', $content->id);
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
