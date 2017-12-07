<?php

namespace App\Models;

use App\Models\QuizQuestion;
use Illuminate\Database\Eloquent\Model;

class UserQuestion extends Model
{
    protected $table = 'quiz_user_questions';

    protected $fillable = ['user_id', 'question_id'];

    public function isCorrect() {
        $question = $this->question;
        $userAnswers = json_decode($this->answers);

        $correctAnswers = $question->options()->where('correct', 1)->get()
                            ->map(function($item) {
                                return "{$item->id}";
                            })->toArray();
        if((count($userAnswers) != count($correctAnswers))
             || count(array_diff($userAnswers, $correctAnswers))) {
            return false;
        }
        return true;
    }

    public function getAnswerId() {
        $userAnswers = json_decode($this->answers);
        return $userAnswers[0];
    }

    public function question() {
        return $this->belongsTo(QuizQuestion::class, 'question_id');
    }
}
