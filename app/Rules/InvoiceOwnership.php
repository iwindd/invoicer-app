<?php

namespace App\Rules;

use App\Models\Invoice;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class InvoiceOwnership implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return (Invoice::findOrFail($value))->user_id == auth()->id();  
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected invoice is not valid for the authenticated user.';
    }
}
