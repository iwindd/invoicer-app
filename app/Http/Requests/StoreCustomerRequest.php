<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
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
            'email' => ['required', 'max:255', 'email', 'unique:customers', 'unique:users'],
            'joined_at' => ['required', 'date'],
            'city_id' => []
        ];
    }
}
