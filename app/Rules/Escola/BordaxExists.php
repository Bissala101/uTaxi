<?php

namespace App\Rules\Escola;

use App\Models\Escola\Facturas;
use Illuminate\Contracts\Validation\Rule;

class BordaxExists implements Rule
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
        return !Facturas::where("n_borderom", $value)->where("escola_id", request()->escola->id)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Este número de bordoax já foi registado';
    }
}
