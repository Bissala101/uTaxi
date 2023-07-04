<?php

namespace App\Traits;
use App\Helpers\Utils;

trait ModelSupport
{
    function foto_link($width = 200){
        return Utils::foto_link($this->foto, $width);
    }
}
