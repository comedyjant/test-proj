<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminAWSS3Controller extends Controller
{
    public function settings() {
        return view('admin.aws.s3.settings');
    }

    public function updateSettings(Request $request) {
        \Settings::set('aws.s3.access_key_id', trim($request->input('access_key_id')));
        \Settings::set('aws.s3.secret_key', trim($request->input('secret_key')));
        \Settings::set('aws.s3.region', trim($request->input('region')));
        \Settings::set('aws.s3.bucket', trim($request->input('bucket')));
        flash('AWS S3 settings have been updated', 'success');
        return redirect()->back();
    }
}
