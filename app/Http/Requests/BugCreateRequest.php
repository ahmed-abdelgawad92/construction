<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Auth;
use Illuminate\Validation\Rule;

class BugCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->type == 'admin';
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'type' => [
                'required', 
                Rule::in(['0', '1'])
            ],
            'attachments' => 'nullable|file'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'title.required' => 'Please enter the ticket title',
            'description.required'  => 'Please enter the ticket description',
            'type.required'  => 'Please enter the ticket description',
            'type.in'  => 'Please choose the ticket type, either a bug (defect) or a new feature',
        ];
    }
}
