<?php

namespace App\Rules\Escola;

use App\Models\Escola\Turmas;
use Illuminate\Contracts\Validation\Rule;

class NotaRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $turma;

    public function __construct(Turmas $turma)
    {
        $this->turma = $turma;
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
        if(trim($value) != "" && $this->turma->is10() && $value > 10){
            return false;
        } else if(trim($value) != "" && $value > 20){
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        if($this->turma->is10()){
            return 'A nota do '.strtoupper(":attribute").' não pode ser maior que 10';
        } else {
            return 'A nota do '.strtoupper(":attribute").' não pode ser maior que 20';
        }
    }
}
