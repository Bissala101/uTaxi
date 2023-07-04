<?php


namespace App\Traits\Escola;

use App\Models\Escola\Permissoes;
use App\Models\Escola\PermissoesEspeciais;

trait HasPermission
{
    function hasPermission($permisson){
            return $this->permissoes()->where("link_id",$permisson)->exists();
    }

    function hasPermissionEsp($permisson){
            return $this->perm_especias()->where("perm_code",$permisson)->exists();
    }

    function permissoes(){
        return $this->hasMany(Permissoes::class, "secretaria_id", "id");
    }

    function perm_especias(){
        return $this->hasMany(PermissoesEspeciais::class, "secretaria_id", "id");
    }


    function permGroupGet($id){
        return $this->permissoes()
            ->join("links", "permissoes.link_id",  "links.id")
            ->whereNotIn("links.id", [39, 54, 58, 41, 27, 28])
            ->where("links.sub_id",$id)
            ->get()
            ->toArray();
    }
}
