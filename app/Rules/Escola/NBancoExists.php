<?php

namespace App\Rules\Escola;

use App\Models\Escola\Bancos;
use Illuminate\Contracts\Validation\Rule;

class NBancoExists implements Rule
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
        return !Bancos::where("numero", $value)->where("escola_id", request()->escola->id)->where("nome", request()->nome)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Um banco com este nome e número já existe';
    }
}
