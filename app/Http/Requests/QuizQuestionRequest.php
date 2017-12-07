<?php

namespace App\Http\Requests;

class QuizQuestionRequest extends Request
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return \Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'question' => 'required',
            'options' => 'required',
            'correct' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'correct.required' => 'You have to choose the correct answer'
        ];
    }
}
