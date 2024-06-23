<?php

namespace App\Http\Requests;

use App\Rules\InvoiceOwnership;
use Illuminate\Foundation\Http\FormRequest;

class PatchInvoiceRequest extends FormRequest
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
            'status' => ['required', 'in:-1,0,1']
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
