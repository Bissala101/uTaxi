<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Model;

class ItemExists implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $item;
    public $msg;

    public function __construct($item, $msg = null)
    {
        $this->item = $item;
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
        return $this->item->where("id", $value)->exists();
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->msg != null){
            return $this->msg;
        } else {
            return 'Item não existe';
        }
    }
}
