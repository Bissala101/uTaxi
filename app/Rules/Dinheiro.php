<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class Dinheiro implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public $msg;

    public function __construct($msg = null)
    {
        $this->msg = $msg;
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
        return preg_match("/^\d{1,13}(\.\d{1,4})?$/", $value);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->msg){
            return $this->msg;
        } else {
            return 'Preço mal formatado';
        }
    }
}
