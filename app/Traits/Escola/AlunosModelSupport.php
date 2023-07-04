<?php


namespace App\Traits\Escola;


use App\Helpers\Escola\UtilsEscola;
use App\Models\Cadeiras;
use App\Models\Escola\AlunosInfo;
use App\Models\Escola\Confirmacoes;
use App\Models\Escola\ContactosAlunos;
use App\Models\Escola\EncarregadosAlunos;
use App\Models\Escola\Escola;
use App\Models\Escola\Facturas;
use App\Models\Escola\MaisInfoAluno;
use App\Models\Escola\Pagamentos;
use App\Models\PagamentosBata;
use App\Models\PagamentosCartao;
use App\Models\pagamentosExameEspecial;
use App\Models\pagamentoUniformeEstagio;

trait AlunosModelSupport
{

    function pagamentosUniformesEstagio(){
        return $this->hasMany(pagamentoUniformeEstagio::class, "aluno_id", "id");
    }

    function pagamentosExameEspecial(){
        return $this->hasMany(pagamentosExameEspecial::class, "aluno_id", "id");
    }

    function pagamentosBatas(){
        return $this->hasMany(PagamentosBata::class, "aluno_id", "id");
    }

    function pagamentosCartaoes(){
        return $this->hasMany(PagamentosCartao::class, "aluno_id", "id");
    }

    function confirmacoes(){
        return $this->hasMany(Confirmacoes::class, "aluno_id", "id");
    }

    function cadeiras(){
        return $this->hasMany(Cadeiras::class, "aluno_id", "id");
    }

    function encarregados(){
        return $this->hasMany(EncarregadosAlunos::class, "aluno_id", "id");
    }

    function contactos(){
        return $this->hasMany(ContactosAlunos::class, "aluno_id", "id");
    }

    function maisInfo(){
        return $this->hasMany(MaisInfoAluno::class, "aluno_id", "id")->first();
    }

    function escola(){
        return $this->belongsTo(Escola::class, "escola_id", "id")->first();
    }

    function facturas(){
        return $this->hasMany(Facturas::class, "aluno_id", "id");
    }

    function pagamentos(){
        return $this->hasMany(Pagamentos::class, "aluno_id", "id");
    }

    function aluno_info(){
        return $this->hasMany(AlunosInfo::class, "aluno_id", "id");
    }

    function getMesesPagoFormatadoEscola($ano_lectivo){

        $escola = $this->escola()->select("id")->first();
        $aluno_info = $this->aluno_info()->where("ano_lectivo", $ano_lectivo)->first();
        $turma = $aluno_info->turma();
        $array = collect(UtilsEscola::mesesEscola());

        $array = $array->filter(function ($item, $key) use($escola, $turma) {

            if($key == 7){
                return $escola->isEscola() && $turma->isJulho();
            } else if($key == 8){
                return false;
            } else {
                return true;
            }

        })->map(function($item, $key) use ($ano_lectivo){

            $data["key"] = $key;
            $is = $this->pagamentos()->where("mes", $key)->where("ano_lectivo", $ano_lectivo)->exists();

            if($is){
                $data["html"] = "<span class='text-success'>".$item." "." <span class='float-right'><i class='fa fa-check'></i></span></span>";
                $data["is_pago"] = true;
                return $data;
            }

            $data["html"] = "<span class='text-danger'>".$item." "."<span class='float-right'><i class='fa fa-times'></i></span></span>";
            $data["is_pago"] = false;
            return $data;
        });

        return $array;
    }

    function getNumeroDeMesesNaoPago($ano){
        $aluno_info = $this->aluno_info()->where("ano_lectivo", $ano)->first();
        $turma = $aluno_info->turma();
        $meses = $this->getMesesPagoFormatadoEscola($ano);

        if($turma->isJulho()) {
            $total = count(\App\Helpers\Escola\UtilsEscola::mesesEscola()) - $meses->where("is_pago", true)->count();
        } else {
            $total = (count(\App\Helpers\Escola\UtilsEscola::mesesEscola()) - 1) - $meses->where("is_pago", true)->count();
        }

        return $total;
    }

    function getMesesOrder($ano_lectivo){
        $mesesPago = $this->getMesesPagoFormatadoEscola($ano_lectivo)->map(function($item, $key){
            $data["item"] = $item;
            $data["id"] = UtilsEscola::mesesEscolaByID($key);
            return $data;
        });
        return $mesesPago;
    }
}
