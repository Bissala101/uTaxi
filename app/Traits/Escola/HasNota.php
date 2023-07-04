<?php


namespace App\Traits\Escola;


use App\Models\Escola\NotasMedio;
use App\Models\Escola\NotasSecundario;

trait HasNota
{
    function notasMedio(){
        return $this->hasMany(NotasMedio::class, "aluno_info_id", "id")->first();
    }

    function notasSecundario(){
        return $this->hasMany(NotasSecundario::class, "aluno_info_id", "id")->first();
    }
}
