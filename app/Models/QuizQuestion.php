<?php

namespace App\Models;

use App\Models\QuizOption;
use App\Models\UserQuestion;
use Illuminate\Database\Eloquent\Model;

class QuizQuestion extends Model
{
    protected $table = 'quiz_questions';

    protected $fillable = ['quiz_id', 'question', 'details', 'weight'];

    public function quiz() {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    public function options() {
        return $this->hasMany(QuizOption::class, 'question_id');
    }

    public function isMultiple() {        
        return $this->options()->where('correct', '1')->count() > 1;
    }

    public function isFirst() {
        return $this->weight == 0;
    }

    public function isLast() {  
        $lastQuestion = static::query()->where('quiz_id', $this->quiz_id)->orderBy('weight', 'desc')->first();      
        return $lastQuestion->id == $this->id;   
    }

    public function getPrev() {
        return static::query()->where('quiz_id', $this->quiz_id)->where('weight', '<', $this->weight)->orderBy('weight', 'desc')->first();
    }

    public function getNext() {        
        return static::query()->where('quiz_id', $this->quiz_id)->where('weight', '>', $this->weight)->orderBy('weight', 'asc')->first();
    }

    public function userAnswer(User $user = null) {
        if(is_null($user)) {
            $user = \Auth::user();
        }
        return $this->hasOne(UserQuestion::class, 'question_id')->where('user_id', $user->id);
    }

    public function answeredByUser(User $user) {
        return !empty($this->userAnswer($user)->first());
    }
}
