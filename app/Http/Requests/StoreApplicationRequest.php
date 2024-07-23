<?php

namespace App\Http\Requests;

use App\Rules\ApplicationAlready;
use Illuminate\Foundation\Http\FormRequest;

class StoreApplicationRequest extends FormRequest
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
            'id' => ['required', 'exists:customers,id', new ApplicationAlready],
            'domain' => ['required', 'max:255']
        ];
    }
}
