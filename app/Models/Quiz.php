<?php

namespace App\Models;

use App\Models\WlaContent;
use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Model;

class Quiz extends Model
{
    protected $table = 'quizzes';

    protected $fillable = ['title', 'description', 'duration', 'flexible'];

    protected $contentType = 'quiz';

    public function questions() {
        return $this->hasMany(QuizQuestion::class, 'quiz_id')->orderBy('weight');
    }

    public function content() {
        return $this->morphOne(WlaContent::class, 'contentable');
    }

    public function getContentType() {
        return $this->contentType;
    }

    public function setFlexibleAttribute($value) {
        $this->attributes['flexible'] = isset($value) ? $value : false;
    }

    public function getUrl() { 
        return route('wla.quiz', [$this->content->course->slug, $this->content->slug]);
    }
}
