<?php

namespace App\Rules;

use App\Helpers\Utils;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\File;

class FotoTempExists implements Rule
{
    /**
     * @var mixed|null
     */
    private $model;
    private $fotos;
    private $msg;

    /**
     * Create a new rule instance.
     *
     * @return void
     */

    public function __construct($model = null, $fotos = null, $msg = null)
    {
        $this->model = $model;
        $this->fotos = $fotos;
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
        if($this->fotos) {
           return true;
        } else {
            if ($this->model) {
                if ($this->model->foto == $value) {
                    return true;
                }
            }
        }

        return File::exists("../" . Utils::$pathFilesName . "/temp/" . $value);
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
            return 'Falha na verificação da foto, por favor envia a foto novamente';
        }
    }
}
