<?php

namespace App\Rules;

use App\Models\Payment;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class PaymentOwnership implements Rule
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
        return (Payment::findOrFail($value))->application === Auth::user()->application;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The selected payment is not valid for the authenticated user.';
    }
}
