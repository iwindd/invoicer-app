<?php

namespace App\Http\Requests;

use App\Rules\InvoiceOwnership;
use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
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
            'id' => ['required', 'exists:invoices,id', new InvoiceOwnership],
            'note' => ['max:255'],
            'start' => ['required', 'date'],
            'end' => ['required', 'date', 'after_or_equal:start'],
            'items' => ['required', 'array'],
            'items.*.name' => ['required'],
            'items.*.amount' => ['required', 'numeric', 'min:0'],
            'items.*.value' => ['required', 'numeric', 'min:0']
        ];
    }
}