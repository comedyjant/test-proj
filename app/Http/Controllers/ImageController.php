<?php

namespace App\Http\Controllers;


use File;
use Image;
use App\Http\Requests;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    
    public function image($type, $template = null, $filename = null){

        if(empty($filename) || !file_exists($imagePath = config("image.types.$type.path").'/'.$filename)){
            if(! file_exists($imagePath = config("image.types.$type.default"))){
                $imagePath = config('image.no-image');
            }
        }

        $mimeType = File::mimeType($imagePath);

        if($mimeType == 'image/gif') {
            $img = File::get($imagePath);
        } else {
            $img = \Image::cache(function($image) use ($imagePath, $template){
                $callback = config("image.templates.$template");

                if (is_callable($callback)) {
                    $callback($image->make($imagePath));
                } else {
                    abort(404);
                }
            });
        }

        return response($img, 200)
             ->header('Content-Type', $mimeType);
    }
}
