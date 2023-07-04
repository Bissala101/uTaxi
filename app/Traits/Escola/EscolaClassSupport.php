<?php


namespace App\Traits\Escola;


use App\Models\Escola\Administrador;
use App\Models\Escola\Alunos;
use App\Models\Escola\Bancos;
use App\Models\Escola\Cargos;
use App\Models\Escola\Classes;
use App\Models\Escola\ConfigPortal;
use App\Models\Escola\Confirmacoes;
use App\Models\Escola\Cursos;
use App\Models\Escola\Departamento;
use App\Models\Escola\Desturma;
use App\Models\Escola\Disciplinas;
use App\Models\Escola\EncarregadosAlunos;
use App\Models\Escola\EscolaConfig;
use App\Models\Escola\Facturas;
use App\Models\Escola\InfoFacturas;
use App\Models\Escola\LogoSystem;
use App\Models\Escola\PrecoMulta;
use App\Models\Escola\Professores;
use App\Models\Escola\Salas;
use App\Models\Escola\Secretaria;
use App\Models\Escola\Turmas;

trait EscolaClassSupport
{
    function alunos(){
        return $this->hasMany(Alunos::class, "escola_id", "id");
    }

    function facturas(){
        return $this->hasMany(Facturas::class, "escola_id", "id");
    }

    function config(){
        return $this->hasMany(EscolaConfig::class,"escola_id", "id")->first()->format();
    }

    function turmas(){
        return $this->hasMany(Turmas::class, "escola_id", "id");
    }

    function admins(){
        return $this->hasMany(Administrador::class, "escola_id", "id");
    }

    function secs(){
        return $this->hasMany(Secretaria::class, "escola_id", "id");
    }

    function departamentos(){
        return $this->hasMany(Departamento::class, "escola_id", "id");
    }

    function cargos(){
        return $this->hasMany(Cargos::class, "escola_id", "id");
    }

    function cursos(){
        return $this->hasMany(Cursos::class, "escola_id", "id");
    }

    function bancos(){
        return $this->hasMany(Bancos::class, "escola_id", "id");
    }

    function desturmas(){
        return $this->hasMany(Desturma::class, "escola_id", "id");
    }

    function salas(){
        return $this->hasMany(Salas::class, "escola_id", "id");
    }

    function classes(){
        return $this->hasMany(Classes::class, "escola_id", "id");
    }

    function disciplinas(){
        return $this->hasMany(Disciplinas::class, "escola_id", "id");
    }

    function precoMulta(){
        return $this->hasMany(PrecoMulta::class, "escola_id", "id")->first();
    }

    function infoFactura(){
        return $this->hasMany(InfoFacturas::class, "escola_id", "id")->first();
    }

    function professores(){
        return $this->hasMany(Professores::class, "escola_id", "id");
    }

    function encarregados(){
        return $this->hasMany(EncarregadosAlunos::class, "escola_id", "id");
    }

    function configPortal(){
        return $this->hasMany(ConfigPortal::class, "escola_id", "id")->first();
    }

    function logosystem(){
        return $this->hasMany(LogoSystem::class, "escola_id", "id");
    }
}
