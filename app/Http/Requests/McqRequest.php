<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class McqRequest extends FormRequest
{
    public function authorize()
    {
        return true; // Allow all authenticated users
    }

    public function rules()
    {
        return [
            'question'        => 'required|string|max:255',
            'option_a'        => 'required|string|max:255',
            'option_b'        => 'required|string|max:255',
            'option_c'        => 'required|string|max:255',
            'option_d'        => 'required|string|max:255',
            'correct_answer'  => 'required|in:a,b,c,d',
            'category_id' => 'required|exists:categories,id',
        ];
    }

    public function messages()
    {
        return [
            'question.required'        => 'Question is required.',
            'option_a.required'        => 'Option A is required.',
            'option_b.required'        => 'Option B is required.',
            'option_c.required'        => 'Option C is required.',
            'option_d.required'        => 'Option D is required.',
            'correct_answer.required'  => 'Please select the correct answer.',
            'correct_answer.in'        => 'Correct answer must be A, B, C, or D.',
            'category_id.required'     => 'Please select a category.',
            'category_id.exists'       => 'Selected category does not exist.',
        ];
    }
}
