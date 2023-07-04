<?php


namespace App\Traits\Escola;


use Illuminate\Support\Facades\Schema;

trait HasDelete
{
    function customDelete(){
        if(Schema::connection($this->connection)->hasColumn($this->table, "is_deleted")){
            $this->update([
                "is_deleted"=>1,
            ]);
        } else {
            throw new \Exception("Coluna is_deleted está em falta na tabela ".$this->table);
        }
    }

    function scopenoDeleted($query){
         return $query->where("is_deleted", 0);
    }

    function scopeDeleted($query){
        return $query->where("is_deleted", 1);
    }
}
