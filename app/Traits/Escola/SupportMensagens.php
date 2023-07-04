<?php


namespace App\Traits\Escola;


use App\Models\Escola\Mensagens;

trait SupportMensagens
{
    function sendArray($providers){
        return Mensagens::insert($providers);
    }
}
