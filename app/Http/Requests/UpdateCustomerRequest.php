<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCustomerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => ['required', 'min:5', 'max:255'],
            'lastname' => ['required', 'min:5', 'max:255'],
            'email' => [
                'required', 
                'max:255', 
                'email', 
                Rule::unique('customers')->ignore($this->route('id')), 
                Rule::unique('users')->ignore($this->route('id'))
            ],
            'joinedAt' => ['required']
        ];
    }
}
