<?php


namespace App\Helpers;


use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Kreait\Laravel\Firebase\Facades\Firebase;

class Utils
{
    static function formataDinheiro($valor){
        return number_format($valor, 2). " Kz";
    }

    static function calcularDistancia($x1, $y1, $x2, $y2) {
        $distancia = sqrt(pow($x2 - $x1, 2) + pow($y2 - $y1, 2));
        return $distancia;
    }
}
