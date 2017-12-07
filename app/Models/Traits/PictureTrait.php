<?php

namespace App\Models\Traits;

use File;
use Illuminate\Http\UploadedFile;

trait PictureTrait {

    public function getPicturesFields() {
        // Redeclare this function
        return ['picture'];
    }

    public function getPictureFolder() {
        $type = static::getTable();
        return config("image.types.$type.path") ?: config("image.types.default.path");
    }

    public function getPictureSrc() {
        if(!$this->picture) {
            return null;
        }
        return $this->getPictureFolder().'/'.$this->picture;
    }

    public function setAttribute($key, $value) {
        $value =  $this->checkPicturesAttribute($key, $value); 
        return parent::setAttribute($key, $value);
    }

    public function checkPicturesAttribute($key, $value) {
        if(in_array($key, $this->getPicturesFields())) {
            if($value instanceof UploadedFile) {
                $this->deletePictureFile($key);
                $name = md5($value->getClientOriginalName().time().\Auth::id()) . "." . $value->guessClientExtension();
                $value->storeAs($this->getPictureFolder(), $name);
                $value = $name;
            }            
        } 
        return $value;
    }

    public function deletePictureFile($field)
    {   
        if(!empty($this->{$field})) {
            $file = $this->getPictureFolder() . '/' . $this->picture;
            if(file_exists($file)) {
                File::delete($file);                
            }
        }
    }




}