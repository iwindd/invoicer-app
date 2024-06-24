<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PatchNoticeRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'invoice' => ['required', 'exists:invoices,id'],
            'image' => ['required', 'image', 'mimes:jpeg,png,jpg', 'max:2048']
        ];
    }
}
