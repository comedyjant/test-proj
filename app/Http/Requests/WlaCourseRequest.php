<?php

namespace App\Http\Requests;

class WlaCourseRequest extends Request
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
        $priceRegex = '/^((\d+\,*)+\.*\d{0,2})$/';      
        return [
            'title' => 'required',
            'price' => 'regex:'.$priceRegex
        ];
    }

    public function messages()
    {
        return [
            'price.regex' => 'Price value is not valid'
        ];
    }
}
