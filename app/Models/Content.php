<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    protected $table = 'content';

    protected $fillable = ['user_id', 'title', 'body'];

    public function user() {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function setBodyAttribute($value){
        $this->attributes['body'] = filter_tags($value);
    }

    public static function getSelectList() {
        $selectList = static::query()->get()
                        ->keyBy('id')
                        ->map(function($item) {
                            return $item->title;
                        })
                        ->prepend(' - Select content - ')
                        ->toArray();
        return $selectList;
    }
}
