<?php


namespace App\Traits\Escola;
use App\Helpers\Escola\UtilsEscola;
use App\Helpers\Utils;
use App\Models\Cadeiras;
use App\Models\CadeirasPagas;
use App\Models\Escola\Alunos;
use App\Models\Escola\AlunosInfo;
use App\Models\Escola\Bancos;
use App\Models\Escola\Confirmacoes;
use App\Models\Escola\ContactosAlunos;
use App\Models\Escola\Disciplinas;
use App\Models\Escola\EncarregadosAlunos;
use App\Models\Escola\Escola;
use App\Models\Escola\Facturas;
use App\Models\Escola\Pagamentos;
use App\Models\Escola\Turmas;
use App\Models\Geral\Usuario;
use App\Models\PagamentosBata;
use App\Models\PagamentosCartao;
use App\Models\pagamentosExameEspecial;
use App\Models\pagamentoUniformeEstagio;
use App\Models\PrecosBata;
use App\Models\PrecosCartao;
use App\Models\PrecosUniformesEstagio;
use App\Models\Stocks;
use App\Rules\Escola\BordaxExists;
use App\Rules\isDinheiro;
use App\Rules\ItemExists;
use Barryvdh\DomPDF\Facade as PDF;
use Google\Auth\Cache\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

trait AlunosControllerSupport
{
    function propinas(Request $request, $escola_id, $id){

        /** @var \App\Models\Escola\Alunos $item */

        $item = $request->escola->alunos()->findOrFail($id);

        $request->validate(
            [
                "ano_lectivo"=>["required", "string"]
            ],
            [
                "ano_lectivo.required"=> "Ocorreu um erro, não é possível selecionar os dados do estudante sem o ano lectivo",
            ]
        );

        $aluno_info = $item->aluno_info()->where("ano_lectivo",$request->ano_lectivo)->firstOrFail();

        $data["html"] = view("escola.secretaria.alunos.options.propinas", ["item"=>$item])->render();
        $data["titulo"] = "Propinas de ".$item->nome;
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function calculaPreco(Request $request, $escola_id, $aluno_id){

        /** @var \App\Models\Escola\Alunos $aluno */
        /** @var \App\Models\Escola\AlunosInfo $aluno_info */
        /** @var \App\Models\Escola\Turmas $turma */
        /** @var \App\Models\Escola\Classes $classe */
        /** @var \App\Models\Escola\PrecosDasClasses $preco */
        /** @var \App\Models\Escola\PrecoMulta $config */

        $aluno = $request->escola->alunos()->findOrFail($aluno_id);


        $request->validate(
            [
                "ano_lectivo"=>["required", "string"],
                "meses"=>["required", "integer"]
            ],
            [
                "ano_lectivo.required"=> "Ocorreu um erro, não é possível selecionar os dados do estudante sem o ano lectivo",
                "meses.required"=> "Por favor seleciona quantos meses o usuário pagou",
                "meses.integer"=> "Meses mal formatados",
            ]
        );

        $cadeiras = $aluno->cadeiras()->get();

        $aluno_info = $aluno->aluno_info()->where("ano_lectivo", $request->ano_lectivo)->firstOrFail();

        $turma = $aluno_info->turma();
        $classe = $turma->classe();
        $preco = $classe->preco();

        if(!$preco){
            $data["estado"] = "erro";
            $data["texto"] = "Nenhum preço foi definido ainda na classe {$classe->nome}, por favor definia os preços";
            return response()->json($data);
        }

        $mes_actual = UtilsEscola::mesesEscolaByID(UtilsEscola::mesFormatado());
        $mesesPago = $aluno->getMesesPagoFormatadoEscola($request->ano_lectivo)->where("is_pago", true)->count();


        if($mesesPago == 0){
            $mesesPago = 1;
        }

        $dias = date("d");
        $config = $request->escola->precoMulta();

        $total = 0;
        $multa = 0;
        $desconto = 0;

        $cadeirasTitulo = "";
        $cadeirasTotal = 0;
        $cadeiras_html = "";

        foreach ($cadeiras as $c){
            $cadeiras_html .= '<span class="text-success">'.$c->disciplina()->nome.': <strong>'.Utils::formataDinheiro($c->preco*$request->meses).' Kz</strong></span><br>';
        }

        for ($i = 1; $i <= $request->meses; $i++) {
            $total += $preco->propina;

            if ($mesesPago == $mes_actual && $config->dias != 0 && $config->dias < $dias) {
                $multa += $preco->multa;
            }

            if($mesesPago < $mes_actual  && $config->dias != 0){
                if ($mesesPago != $mes_actual) {
                    $multa += $preco->multa;
                }
            }

            foreach ($cadeiras as $c){
                $cadeirasTotal += $c->preco;
            }

            $desconto += $aluno->desconto;
            $mesesPago++;
        }

        $data["cadeiras"] = null;


        if($cadeiras->count() != 0){
            $cadeirasTitulo = "<div class='font-weight-bold mt-2'>--- Cadeiras ---</div>";
        }

        $data["meses"] = $total;
        $data["multa"] = $multa;
        $data["desconto"] = $desconto;
        $data["total"] = ($total+$multa)-$desconto;

        $data["estado"] = "ok";

        $data["html"] = '
            <div class="mb-2 mt-2">
            <span class="text-success">Meses: <strong>'.Utils::formataDinheiro($total).' Kz</strong></span><br>
            <span class="text-success">Multa: <strong>'.Utils::formataDinheiro($multa).' Kz</strong></span><br>
            <span class="text-success">Desconto: <strong>'.Utils::formataDinheiro($desconto).' Kz</strong></span><br>
            '.$cadeirasTitulo.'
            '.$cadeiras_html.'<br>
            <span class="text-success">Total: <strong>'.Utils::formataDinheiro(($total+$multa+$cadeirasTotal)-$desconto).' Kz</strong></span><br>
            </div>
        ';

        return response()->json($data);
    }

    function registarPropina(Request $request, $escola_id, $aluno_id){

        /** @var \App\Models\Escola\Alunos $aluno */
        /** @var \App\Models\Escola\AlunosInfo $aluno_info */
        /** @var \App\Models\Escola\Turmas $turma */
        /** @var \App\Models\Escola\Classes $classe */
        /** @var \App\Models\Escola\PrecosDasClasses $preco */
        /** @var \App\Models\Escola\PrecoMulta $config */

        $aluno = $request->escola->alunos()->findOrFail($aluno_id);

        $request->validate(
            [
                "ano_lectivo"=>["required", "string"],
                "meses"=>["required", "integer"],
                "tipo_pagamento" => ["required","integer", "max:1"],
                "n_bord" => ["required_if:tipo_pagamento,==,1", new BordaxExists()],
                "banco_id" => ["required_if:tipo_pagamento,==,1", new ItemExists((new Bancos())->where("escola_id", $request->escola->id), "O banco enviado não existe")],
            ],
            [
                "ano_lectivo.required"=> "Ocorreu um erro, não é possível selecionar os dados do estudante sem o ano lectivo",
                "meses.required"=> "Por favor seleciona quantos meses o usuário pagou",
                "meses.integer"=> "Meses mal formatados",
                "banco_id.required"=> "Por favor seleciona um banco",
                "tipo_pagamento.required"=> "Por favor escolhe um tipo de pagamento para continuar",
                "tipo_pagamento.integer"=> "Tipo de pagamento mal formatado",
                "tipo_pagamento.max"=> "Tipo de pagamento mal formatado",
                "n_bord.required_if"=> "Por favor insira o número do bordoax",
            ]
        );

        $aluno_info = $aluno->aluno_info()->where("ano_lectivo", $request->ano_lectivo)->firstOrFail();

        $turma = $aluno_info->turma();
        $classe = $turma->classe();
        $preco = $classe->preco();

        if(!$preco){
            $data["estado"] = "erro";
            $data["texto"] = "Nenhum preço foi definido ainda na classe {$classe->nome}, por favor definia os preços";
            return response()->json($data);
        }


        $factura = DB::transaction(function () use ($request, $aluno, $preco){

            $mes_actual = UtilsEscola::mesesEscolaByID(UtilsEscola::mesFormatado());
            $mesesPago = $aluno->getMesesPagoFormatadoEscola($request->ano_lectivo)->where("is_pago", true)->count();

            if($mesesPago == 0){
                $mesesPago = 1;
            } else {
                $mesesPago++;
            }

            $dias = date("d");
            $config = $request->escola->precoMulta();

            $total = 0;
            $multa = 0;
            $desconto = 0;

            $factura = Facturas::create([
                "aluno_id"=>$aluno->id,
                "escola_id"=>$request->escola->id,
                "tempo"=>time(),
                "ano_lectivo"=>$request->ano_lectivo,
                "tipo_pagamento"=>$request->tipo_pagamento,
                "n_borderom"=>$request->n_bord != null ? $request->n_bord:"",
                "banco_id"=> $request->tipo_pagamento == 1 ? $request->banco_id:0,
                "tipo_servico"=>Facturas::TIPO_PROPINA,
                "valor_unico"=> 0,
                "sec_id"=>$request->sec->id,
                "uid_pago"=>0,
            ]);

            $array = [];
            $cadeirasArray = [];

            $cadeiras = $aluno->cadeiras()->get();

            for ($i = 1; $i <= $request->meses; $i++) {

                $total += $preco->propina-$aluno->desconto;
                $multa_temp = 0;

                if ($mesesPago == $mes_actual && $config->dias != 0 && $config->dias < $dias) {
                    $multa += $preco->multa;
                    $multa_temp = $preco->multa;
                }

                if($mesesPago < $mes_actual  && $config->dias != 0){
                    if ($mesesPago != $mes_actual) {
                        $multa += $preco->multa;
                        $multa_temp = $preco->multa;
                    }
                }

                $desconto += $aluno->desconto;
                $totalCadeiras = 0;

                foreach ($cadeiras as $c){
                   $totalCadeiras += $c->preco;
                }

                $p = Pagamentos::create([
                    "escola_id"=>$request->escola->id,
                    "aluno_id"=>$aluno->id,
                    "preco"=>$preco->propina,
                    "factura_id"=>$factura->id,
                    "multa"=>$multa_temp,
                    "mes"=>UtilsEscola::mesesEscolaReverseByID($mesesPago),
                    "ano_lectivo"=>$request->ano_lectivo,
                    "desconto"=>$aluno->desconto,
                    "cadeiras"=>$totalCadeiras,
                ]);

                foreach ($cadeiras as $c){
                    CadeirasPagas::create([
                        "nome"=>$c->disciplina()->nome,
                        "preco"=>$c->preco,
                        "pagamento_id"=>$p->id,
                    ]);
                }

                $mesesPago++;
            }

            return $factura;
        });



        $factura_html = view("escola.facturas.propina", ["id"=>$factura->id])->render();
        $data["factura"] = $factura_html;
        $data["estado"] = "ok";
        $data["titulo"] = "Factura ".$factura->getId();
        return response()->json($data);
    }


    function editar(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.editar", ["item"=>$item])->render();
        $data["titulo"] = "Editar aluno ".$item->nome;
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function editarSend(Request $request, $escola_id, $id){

        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);

        $request->validate(
            [
                "ano_lectivo" => ["required","string"],
                "nome" => ["required","string"],
                "numero" => ["required","integer"],
                "turma_id" => ["required","string", new ItemExists((new Turmas())->where("escola_id", $request->escola->id), "A turma que enviaste não existe nesta instituição")],
                "sexo" => ["required","integer", "max:1"],
                "tipo_escola" => ["required","integer", "min:0", "max:1"],
                "data_nascimento" => ["nullable", "date_format:d/m/Y"],
                "telefones.*.telefone"=>["nullable", "integer"],
            ],
            [
                "nome.required"=>"Por favor insira o nome do aluno",
                "numero.required"=>"Por favor insira um número",
                "numero.integer"=>"Número do aluno inválido",
                "data_nascimento.date_format"=>"Data de nascimento inválida",
                "sexo.required" => "Por favor informa o sexo",
                "sexo.integer" => "Sexo mal formatado",
                "sexo.max" => "Sexo mal formatado",
                "telefones.*.telefone.integer"=>"Número de telefone mal formatado",
                "telefones.*.telefone.between"=>"Número de telefone mal formatado",
                "ano_lectivo.required"=>"Falha na verificação do ano lectivo",
                "ano_lectivo.string"=>"Ano lectio mal formatado",
            ]
        );

        $aluno_info = $item->aluno_info()->where("ano_lectivo", $request->ano_lectivo)->firstOrFail();

        $search = AlunosInfo::where("escola_id", \request()->escola->id)
            ->where("ano_lectivo", $request->ano_lectivo)
            ->where("numero", $request->numero)
            ->where("turma_id", $aluno_info->turma_id)
            ->where("id", "!=",$aluno_info->id)->first();

        if($search){
            $data["texto"] = "Já existe um aluno nesta turma com este número neste ano lectivo";
            $data["estado"] = "erro";
            return response()->json($data);
        }


        $item->nome = $request->nome;
        $item->sexo = $request->sexo;
        $item->tipo_escola = $request->tipo_escola;
        $item->save();

        $aluno_info->numero = $request->numero ? $request->numero:0;
        $aluno_info->turma_id = $request->turma_id;
        $aluno_info->save();

        $maisInfo = $item->maisInfo();

        $maisInfo->update([
            "aluno_id"=>$item->id,
            "pai"=>$request->nome_pai,
            "mae"=>$request->nome_mae,
            "bilhete_n"=>$request->bi,
            "natural_de"=>$request->local_nascimento,
            "casa_n"=>$request->casa_n,
            "escola_id"=>$request->escola->id,
            "nasc"=>$request->data_nascimento,
        ]);

        $item->contactos()->delete();

        if(is_array($request->telefones)) {
            $contactos = array();
            foreach ($request->telefones as $c) {
                if(trim($c["telefone"]) != "") {
                    $contactos[] = [
                        "escola_id" => $request->escola->id,
                        "aluno_id" => $item->id,
                        "numero" => $c["telefone"],
                    ];
                }
            }

            ContactosAlunos::insert($contactos);
        }

        $data["estado"] = "ok";
        return response()->json($data);
    }

    function info(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.info", ["item"=>$item])->render();
        $data["titulo"] = "Informações de ".$item->nome;
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function desconto(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.desconto", ["item"=>$item])->render();
        $data["titulo"] = "Editar descontos de ".$item->nome;
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function facturas(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.facturas", ["item"=>$item])->render();
        $data["titulo"] = "Facturas de ".$item->nome;
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function actualizarDesconto(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);

        $request->validate([
            "valor"=>["required", new isDinheiro("Valor do desconto inválido")],
        ]);


        $item->desconto = $request->valor;
        $item->save();

        $data["estado"] = "ok";
        return response()->json($data);
    }

    function encarregados(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.encarregados", ["item"=>$item])->render();
        $data["titulo"] = "Permissões de encarregados ".$item->nome;
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function atribuir(Request $request, $escola_id, $id){

        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);

        $request->validate(
            [
                "id"=>["required","string"],
            ],
            [
                "id.required"=>"Por favor introduza um ID para continuar",
                "id.string"=>"O ID fornecido está mal formatado",
            ]
        );

        $id = $request->id;
        $user = Usuario::orWhere("id", $id)->orWhere("nome_usuario", $id)->orWhere("email", $id)->orWhere("telefone", $id)->orWhere("wap_email", $id)->first();

        if(!$user) {
            $data["estado"] = "erro";
            $data["texto"] = "Nenhum usuário com esta identificação foi encontrado!";
            return response()->json($data);
        }

        if($item->encarregados()->where("uid", $user->id)->exists())
        {
            $data["estado"] = "erro";
            $data["texto"] = "Um usuário com esta identificão já tem controlo deste aluno, esta identificação pertence ao {$user->nome_completo()}";
            return response()->json($data);
        }

        $enc = EncarregadosAlunos::create([
            "uid"=>$user->id,
            "aluno_id"=>$item->id,
            "escola_id"=>$request->escola->id,
        ]);

        $data["estado"] = "ok";
        return response()->json($data);
    }

    function removerEncarregado(Request $request, $escola_id, $id, $enc_id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $item->encarregados()->where("id", $enc_id)->firstOrFail()->delete();
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function aut(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.aut", ["item"=>$item])->render();
        $data["titulo"] = "Autenticação de ".$item->nome;
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function editarAut(Request $request, $escola_id, $id){

        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);

        $request->validate(
            [
                "senha"=>["required","string"],
            ],
            [
                "senha.required"=>"Por favor introduza a senha para continuar",
                "senha.string"=>"A senha fornecida é inválida",
            ]
        );

        $item->senha = Hash::make($request->senha);
        $item->save();

        $data["estado"] = "ok";
        return response()->json($data);
    }

    function opEliminar(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.eliminar", ["item"=>$item])->render();
        $data["titulo"] = "Opções de eliminação de ".$item->nome;
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function opEliminarSet(Request $request, $escola_id, $id){

        /** @var \App\Models\Escola\Alunos  $item */
        $item = $request->escola->alunos()->findOrFail($id);

        $request->validate(
            [
                "estado"=>["required","integer","max:3"],
            ],
            [
                "estado.required"=>"Ocorreu um erro ao tentar executar esta ação",
                "estado.integer"=>"Dados mal formatados",
                "estado.max"=>"Dados mal formatados",
            ]
        );

        if($request->estado != 3) {
            $item->estado = $request->estado;
            $item->save();
        } else {
            $item->encarregados()->delete();
            $item->pagamentos()->delete();
            $item->facturas()->delete();
            $item->aluno_info()->delete();
            $item->maisInfo()->delete();
            $item->contactos()->delete();
            $item->delete();
        }

        $data["estado"] = "ok";
        return response()->json($data);
    }

    function declaracao(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);

        $info = $item->maisInfo();
        if(!$info->pai || !$info->mae || !$info->nasc || !$info->bilhete_n){
            $data["texto"] = "Antes de imprimir a declaração do aluno você deve actualizar as informações pessoais dele";
            $data["estado"] = "erro";
            return response()->json($data);
        }

        $data["html"] = view("escola.secretaria.alunos.options.declaracao", ["item"=>$item])->render();
        $data["titulo"] = "Imprimir declaração de ".$item->nome;
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function imprimir_declaracao(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);

        $info = $item->maisInfo();

        if(!$info->pai || !$info->mae || !$info->nasc || !$info->bilhete_n){
            $data["texto"] = "Antes de imprimir a declaração do aluno você deve actualizar as informações pessoais dele";
            $data["estado"] = "erro";
            return response()->json($data);
        }

        $data["html"] = view("escola.secretaria.alunos.options.imprimir_declaracao", ["item"=>$item, "request"=>$request])->render();
        $data["titulo"] = "Declaração de ".$item->nome;
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function imprimir_declaracao_pdf(Request $request, $escola_id, $id){


        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);

        $info = $item->maisInfo();
        if(!$info->pai || !$info->mae || !$info->nasc || !$info->bilhete_n){
            $data["texto"] = "Antes de imprimir a declaração do aluno você deve actualizar as informações pessoais dele";
            $data["estado"] = "erro";
            return response()->json($data);
        }

        PDF::setOptions(['dpi' => 10, 'defaultFont' => 'sans-serif']);

        $request->servico =0;
        $pdf = PDF::loadView('escola.secretaria.alunos.options.imprimir_declaracao', ["item"=>$item, "request"=>$request]);
        return $pdf->stream("Declaração de ".$item->nome);
    }

    function imprimir_lista(Request $request){
        $data["html"] = view("escola.secretaria.all.imprimir_lista.lista_alunos")->render();
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function cadeiras(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.cadeiras", ["item"=>$item])->render();
        $data["estado"] = "ok";
        $data["titulo"] = "Cadeiras de ".$item->nome;
        return response()->json($data);
    }

    function addCadeira(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);

        $request->validate([
            "disciplina_id"=>["required", new ItemExists((new Disciplinas())->noDeleted(), "Disciplina não encontrada!"), "unique:cadeiras"],
            "preco"=>["required",new isDinheiro("Preço inválido")]
            ],[
                "disciplina_id.required"=>"Por favor informa a disciplina",
                "disciplina_id.unique"=>"Está cadeira já esta registada!",
                "preco.required"=>"Por favor adiciona um preço",
            ]
        );

        $c = new Cadeiras();
        $c->aluno_id = $item->id;
        $c->preco = $request->preco;
        $c->disciplina_id = $request->disciplina_id;
        $c->escola_id = $escola_id;
        $c->tempo = time();
        $c->save();


        $data["estado"] = "ok";
        return response()->json($data);
    }

    function eliminarCadeira(Request $request, $escola_id, $id, $cadeira_id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);

        $c = $item->cadeiras()->find($cadeira_id);

        if($c){
            $c->delete();
        }

        $data["estado"] = "ok";
        return response()->json($data);
    }

    function confirmacao(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.confirmacao", ["item"=>$item])->render();
        $data["estado"] = "ok";
        $data["titulo"] = "Confirmar aluno ".$item->nome;
        return response()->json($data);
    }

    function confirmacaoSend(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);

        $request->validate([
            "turma_id"=>["required", new ItemExists(new Turmas(), "Turma inexistente")],
            "ano_lectivo"=>["required"],
            "tipo_pagamento" => ["required","integer", "max:1"],
            "n_bord" => ["required_if:tipo_pagamento,==,1", new BordaxExists()],
        ],[
            "tipo_pagamento.required"=>"Por favor seleciona um tipo de pagamento",
            "tipo_pagamento.integer"=>"Tipo de pagamento mal formatado",
            "tipo_pagamento.max"=>"Tipo de pagamento mal formatado",
            "n_bord.required_if"=>"Por favor insira o número do bordoax",
        ]);

        $aluno_info = $item->aluno_info()->anolectivo($request->ano)->first();
        $turma = $aluno_info->turma();
        $preco = $turma->classe()->preco()->confirmacao;

        $alunoInfo = $item->aluno_info()->anolectivo($request->ano_lectivo)->first();

        if($alunoInfo){
            $data["estado"] = "erro";
            $data["texto"] = "Este aluno já está ou já foi confirmado para o ano lectivo selecionado!";
            return response()->json($data);
        }

        $ano_count = date("Y")+1;
        $factura = Facturas::create([
            "escola_id"=>$request->escola->id,
            "aluno_id"=>$id,
            "tempo"=>time(),
            "tipo_pagamento"=>$request->tipo_pagamento,
            "n_borderom"=>$request->n_bord != null ? $request->n_bord:"",
            "banco_id"=> $request->tipo_pagamento == 1 ? $request->banco_id:0,
            "tipo_servico"=>Facturas::TIPO_CONFIRMACAO,
            "valor_unico"=>$preco,
            "sec_id"=>$request->sec->id,
            "disciplina_id"=>0,
            "uid_pago"=>0,
            "ano_lectivo" => date("Y")."/".$ano_count,
        ]);

        $info = new AlunosInfo();
        $info->aluno_id = $id;
        $info->numero = 0;
        $info->turma_id = $request->turma_id;
        $info->ano_lectivo = $request->ano_lectivo;
        $info->escola_id = $escola_id;
        $info->estado = 0;
        $info->save();

        $confirmacao = new Confirmacoes();
        $confirmacao->ano_lectivo = $request->ano_lectivo;
        $confirmacao->aluno_id = $id;
        $confirmacao->tempo = time();
        $confirmacao->escola_id = $escola_id;
        $confirmacao->factura_id = $factura->id;
        $confirmacao->curso_id = $turma->curso()->id;
        $confirmacao->classe_id = $turma->classe()->id;
        $confirmacao->turno_id = $turma->turno_id;
        $confirmacao->save();


        $data["estado"] = "ok";
        $data["factura"] = view("escola.facturas.confirmacao", ["id"=> $factura->id])->render();
        return response()->json($data);
    }

    function pagamentos(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.pagamentos", ["item"=>$item])->render();
        $data["estado"] = "ok";
        $data["titulo"] = "Pagamentos de ".$item->nome;
        return response()->json($data);
    }

    function pagamentosCartao(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.cartao", ["item"=>$item])->render();
        $data["estado"] = "ok";
        $data["titulo"] = "Pagamentos de cartões de ".$item->nome;
        return response()->json($data);
    }

    function pagamentosCartaoRegistar(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $preco = PrecosCartao::first();

        $request->validate([
            "tipo_pagamento" => ["required","integer", "max:1"],
            "n_bord" => ["required_if:tipo_pagamento,==,1", new BordaxExists()],
        ],[
            "tipo_pagamento.required"=>"Por favor seleciona um tipo de pagamento",
            "tipo_pagamento.integer"=>"Tipo de pagamento mal formatado",
            "tipo_pagamento.max"=>"Tipo de pagamento mal formatado",
            "n_bord.required_if"=>"Por favor insira o número do bordoax",
        ]);


        $ano_count = date("Y")+1;

        $factura = Facturas::create([
            "escola_id"=>$request->escola->id,
            "aluno_id"=>$id,
            "tempo"=>time(),
            "tipo_pagamento"=>$request->tipo_pagamento,
            "n_borderom"=>$request->n_bord != null ? $request->n_bord:"",
            "banco_id"=> $request->tipo_pagamento == 1 ? $request->banco_id:0,
            "tipo_servico"=>Facturas::TIPO_CARTAO,
            "valor_unico"=>$item->tipo_escola == 0 ? $preco->crescente:$preco->argosys,
            "sec_id"=>$request->sec->id,
            "disciplina_id"=>0,
            "uid_pago"=>0,
            "ano_lectivo" => date("Y")."/".$ano_count,
        ]);

        $cartao = PagamentosCartao::create([
            "aluno_id"=>$id,
            "preco"=>$item->tipo_escola == 0 ? $preco->crescente:$preco->argosys,
            "tempo"=>time(),
            "tipo_escola"=>$item->tipo_escola,
            "factura_id"=>$factura->id,
        ]);

        $data["factura"] = view("escola.facturas.cartao", ["id"=>$factura->id])->render();
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function pagamentosCartaoEliminar(Request $request, $escola_id, $id, $pagamento_id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $pagamento = $item->pagamentosCartaoes()->findOrFail($pagamento_id);

        $pagamento->factura()->delete();
        $pagamento->delete();

        $data["estado"] = "ok";
        return response()->json($data);
    }


    function pagamentosBata(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.uniforme", ["item"=>$item])->render();
        $data["estado"] = "ok";
        $data["titulo"] = "Pagamentos de uniforme/bata de ".$item->nome;
        return response()->json($data);
    }

    function pagamentosBataRegistar(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $preco = PrecosBata::first();

        $stock = Stocks::first();

        if($stock->batas == 0 && $item->tipo_escola == 1){
            $data["estado"] = "erro";
            $data["texto"] = "Já não tem nenhuma bata em stock, por favor actualiza o stock de batas";
            return response()->json($data);
        }

        if($stock->uniformes == 0 && $item->tipo_escola == 0){
            $data["estado"] = "erro";
            $data["texto"] = "Já não tem nenhum uniforme em stock, por favor actualiza o stock de uniformes";
            return response()->json($data);
        }

        $request->validate([
            "tipo_pagamento" => ["required","integer", "max:1"],
            "n_bord" => ["required_if:tipo_pagamento,==,1", new BordaxExists()],
        ],[
            "tipo_pagamento.required"=>"Por favor seleciona um tipo de pagamento",
            "tipo_pagamento.integer"=>"Tipo de pagamento mal formatado",
            "tipo_pagamento.max"=>"Tipo de pagamento mal formatado",
            "n_bord.required_if"=>"Por favor insira o número do bordoax",
        ]);


        $ano_count = date("Y")+1;

        $factura = Facturas::create([
            "escola_id"=>$request->escola->id,
            "aluno_id"=>$id,
            "tempo"=>time(),
            "tipo_pagamento"=>$request->tipo_pagamento,
            "n_borderom"=>$request->n_bord != null ? $request->n_bord:"",
            "banco_id"=> $request->tipo_pagamento == 1 ? $request->banco_id:0,
            "tipo_servico"=>Facturas::TIPO_UNIFORME,
            "valor_unico"=>$item->tipo_escola == 0 ? $preco->crescente:$preco->argosys,
            "sec_id"=>$request->sec->id,
            "disciplina_id"=>0,
            "uid_pago"=>0,
            "ano_lectivo" => date("Y")."/".$ano_count,
        ]);

        $cartao = PagamentosBata::create([
            "aluno_id"=>$id,
            "preco"=>$item->tipo_escola == 0 ? $preco->crescente:$preco->argosys,
            "tempo"=>time(),
            "tipo_escola"=>$item->tipo_escola,
            "factura_id"=>$factura->id,
        ]);

        $stock->decrement($item->tipo_escola == 0 ? "uniformes":"batas");

        $data["factura"] = view("escola.facturas.bata", ["id"=>$factura->id])->render();
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function pagamentosBataEliminar(Request $request, $escola_id, $id, $pagamento_id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $pagamento = $item->pagamentosBatas()->findOrFail($pagamento_id);

        $pagamento->factura()->delete();
        $pagamento->delete();

        $data["estado"] = "ok";
        return response()->json($data);
    }


    function pagamentosExame(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.exame", ["item"=>$item])->render();
        $data["estado"] = "ok";
        $data["titulo"] = "Pagamentos de exames especiais ".$item->nome;
        return response()->json($data);
    }

    function pagamentosExameRegistar(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);

        $request->validate([
            "disciplina_id"=>["required", new ItemExists(new Disciplinas())],
            "tipo_pagamento" => ["required","integer", "max:1"],
            "n_bord" => ["required_if:tipo_pagamento,==,1", new BordaxExists()],
        ],[
            "tipo_pagamento.required"=>"Por favor seleciona um tipo de pagamento",
            "tipo_pagamento.integer"=>"Tipo de pagamento mal formatado",
            "tipo_pagamento.max"=>"Tipo de pagamento mal formatado",
            "n_bord.required_if"=>"Por favor insira o número do bordoax",
        ]);

        $disciplina = $request->escola->disciplinas()->findOrFail($request->disciplina_id);
        $preco = $disciplina->preco();

        if(!$preco){
            $data["estado"] = "erro";
            $data["texto"] = "Nenhum preço definido a esta disciplina para registar o exame!";
            return response()->json($data);
        }


        $ano_count = date("Y")+1;

        $factura = Facturas::create([
            "escola_id"=>$request->escola->id,
            "aluno_id"=>$id,
            "tempo"=>time(),
            "tipo_pagamento"=>$request->tipo_pagamento,
            "n_borderom"=>$request->n_bord != null ? $request->n_bord:"",
            "banco_id"=> $request->tipo_pagamento == 1 ? $request->banco_id:0,
            "tipo_servico"=>Facturas::TIPO_EXAME_ESPECIAL,
            "valor_unico"=>$preco->preco,
            "sec_id"=>$request->sec->id,
            "disciplina_id"=>0,
            "uid_pago"=>0,
            "ano_lectivo" => date("Y")."/".$ano_count,
        ]);

        $cartao = pagamentosExameEspecial::create([
            "disciplina_id"=>$disciplina->id,
            "aluno_id"=>$id,
            "preco"=>$preco->preco,
            "tempo"=>time(),
            "tipo_escola"=>$item->tipo_escola,
            "factura_id"=>$factura->id,
        ]);

        $data["factura"] = view("escola.facturas.exame", ["id"=>$factura->id])->render();
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function pagamentosExameEliminar(Request $request, $escola_id, $id, $pagamento_id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $pagamento = $item->pagamentosExameEspecial()->findOrFail($pagamento_id);

        $pagamento->factura()->delete();
        $pagamento->delete();

        $data["estado"] = "ok";
        return response()->json($data);
    }



    function pagamentosUniformeEstagio(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $data["html"] = view("escola.secretaria.alunos.options.uniforme_estagio", ["item"=>$item])->render();
        $data["estado"] = "ok";
        $data["titulo"] = "Pagamentos de uniforme de estágio de ".$item->nome;
        return response()->json($data);
    }

    function pagamentosUniformeEstagioRegistar(Request $request, $escola_id, $id){
        /** @var \App\Models\Escola\Alunos $item */

        $stock = Stocks::first();

        if($stock->uniforme_estagio == 0){
            $data["estado"] = "erro";
            $data["texto"] = "Já não tem nenhum uniforme de estágio em stock, por favor actualiza o stock de uniformes de estágio";
            return response()->json($data);
        }

        $item = $request->escola->alunos()->findOrFail($id);
        $preco = PrecosUniformesEstagio::first();

        $request->validate([
            "tipo_pagamento" => ["required","integer", "max:1"],
            "n_bord" => ["required_if:tipo_pagamento,==,1", new BordaxExists()],
        ],[
            "tipo_pagamento.required"=>"Por favor seleciona um tipo de pagamento",
            "tipo_pagamento.integer"=>"Tipo de pagamento mal formatado",
            "tipo_pagamento.max"=>"Tipo de pagamento mal formatado",
            "n_bord.required_if"=>"Por favor insira o número do bordoax",
        ]);


        $ano_count = date("Y")+1;

        $factura = Facturas::create([
            "escola_id"=>$request->escola->id,
            "aluno_id"=>$id,
            "tempo"=>time(),
            "tipo_pagamento"=>$request->tipo_pagamento,
            "n_borderom"=>$request->n_bord != null ? $request->n_bord:"",
            "banco_id"=> $request->tipo_pagamento == 1 ? $request->banco_id:0,
            "tipo_servico"=>Facturas::TIPO_UNIFORME_ESTAGIO,
            "valor_unico"=>$preco->preco,
            "sec_id"=>$request->sec->id,
            "disciplina_id"=>0,
            "uid_pago"=>0,
            "ano_lectivo" => date("Y")."/".$ano_count,
        ]);

        $cartao = pagamentoUniformeEstagio::create([
            "aluno_id"=>$id,
            "preco"=>$preco->preco,
            "tempo"=>time(),
            "factura_id"=>$factura->id,
        ]);

        $stock->decrement("uniforme_estagio");

        $data["factura"] = view("escola.facturas.uniforme_estagio", ["id"=>$factura->id])->render();
        $data["estado"] = "ok";
        return response()->json($data);
    }

    function pagamentosUniformeEstagioEliminar(Request $request, $escola_id, $id, $pagamento_id){
        /** @var \App\Models\Escola\Alunos $item */
        $item = $request->escola->alunos()->findOrFail($id);
        $pagamento = $item->pagamentosBatas()->findOrFail($pagamento_id);

        $pagamento->factura()->delete();
        $pagamento->delete();

        $data["estado"] = "ok";
        return response()->json($data);
    }

}
