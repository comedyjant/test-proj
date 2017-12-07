<?php

namespace App\Http\Controllers;

use Aws\S3\S3Client;
use Aws\S3\PostObjectV4;  
use Illuminate\Http\Request;

class UploadController extends Controller
{
    protected $client;

    public function __construct(S3Client $client) {
        $this->client = $client;
    }

    public function as3Signed() {
        $options = [
            ['bucket' => settings('aws.s3.bucket')],
            ['starts-with', '$key', ''],
            ['success_action_status' => '201'],
            ['acl' => 'public-read'],
            ['starts-with', '$key', 'courses/'],
            ['starts-with', '$Content-Type', ''],
        ];

        $formInputs = [
            'acl' => 'public-read',
            'success_action_status' => 201
        ];

        $postObject = new \Aws\S3\PostObjectV4(
            $this->client,
            settings('aws.s3.bucket'),
            $formInputs,
            $options,
            '+3 minutes'
        );
        return response()->json([
            'attributes' => $postObject->getFormAttributes(),
            'additionalData' => $postObject->getFormInputs()
        ]);
    }

    public function uploadContentImage(Request $request) {
        if($image = $request->file('file')) {
            $folder = config("image.types.images.path");
            $name = md5($image->getClientOriginalName().time().\Auth::id()) . "." . $image->guessClientExtension();             
            $image->storeAs($folder, $name);
            $path = route('image', ['images', 'max_800x', $name]);
            return response()->json([
                'status' => 'success',
                'content' => $path
            ]);
        }
        return response()->json(['status' => 'error']);
    }
}
