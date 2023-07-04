<?php
/*
    Projecto: wapangola
    Criado por Adriano Ernesto
    Em: 25/08/2021, 16:53
*/


namespace App\Traits;


use Illuminate\Support\Facades\Schema;

trait HasEliminado
{
    function customDelete(){
        if(Schema::connection($this->connection)->hasColumn($this->table, "is_eliminado")){
            $this->update([
                "is_eliminado"=>1,
            ]);
        } else {
            throw new \Exception("Coluna is_eliminado está em falta na tabela ".$this->table);
        }
    }

    function scopeactivo($query){
        return $query->where("is_eliminado", 0);
    }

    function scopeeliminado($query){
        return $query->where("is_eliminado", 1);
    }
}
