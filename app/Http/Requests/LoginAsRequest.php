<?php

namespace App\Http\Requests;

use App\Rules\Root;
use Illuminate\Foundation\Http\FormRequest;

class LoginAsRequest extends FormRequest
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
            'id' => ['required', new Root]
        ];
    }

    /**
     * Modify the input data before validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'id' => $this->route('id'),
        ]);
    }
}
