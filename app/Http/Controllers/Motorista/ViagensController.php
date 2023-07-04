<?php

namespace App\Http\Controllers\Motorista;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\Motoristas;
use App\Models\User;
use App\Models\Viagens;
use App\Rules\Dinheiro;
use Illuminate\Http\Request;

class ViagensController extends Controller
{
    public function index()
    {

    }

    function nova_viagem(){
        return view("motorista.viagens.nova_viagem");
    }

    function processViagem(Request $request){

        $request->validate([
            "p_d_y"=>["required", "integer"],
            "p_d_x"=>["required", "integer"],
            "cliente_id"=>["required", "exists:users,id"],
        ]);

        $cliente = User::findOrFail($request->get("cliente_id"));

        $motorista = auth()->user();
        $carro = $motorista->carro();

        $km = Utils::calcularDistancia($request->get("p_d_x"), $request->get("p_d_y"), $motorista->posicao_x, $motorista->posicao_y);
        $preco = $carro->preco * $km;

        return view("motorista.viagens.confirmar_viagem", ["cliente"=>$cliente, "preco"=>$preco, "km" => $km]);
    }

    function viagemConfirmar(Request $request){
        $motorista = Motoristas::findOrFail($request->get("motorista_id"));
        $carro = $motorista->carro();


        $request->validate([
            "p_d_y"=>["required", "integer"],
            "p_d_x"=>["required", "integer"],
        ]);

        
        $km = Utils::calcularDistancia($request->get("p_d_x"), $request->get("p_d_y"), $motorista->posicao_x, $motorista->posicao_y);
        $preco = $carro->preco * $km;

        return view("motorista.viagens.confirmar_viagem", ["cliente"=>$cliente, "preco"=>$preco, "km" => $km]);
    }

    function publicarViagem(Request $request){

        $request->validate([
            "p_d_y"=>["required", "integer"],
            "p_d_x"=>["required", "integer"],
            "cliente_id"=>["required", "exists:users,id"],
        ]);

        $motorista = auth()->guard("motorista")->user();
        $carro = $motorista->carro();

        $motorista = auth()->user();

        $km = Utils::calcularDistancia($request->get("p_d_x"), $request->get("p_d_y"), $motorista->posicao_x, $motorista->posicao_y);
        $preco = $carro->preco * $km;

        $viagem = Viagens::create([
            "preco" => $preco,
            "motorista_id" => auth()->guard("motorista")->id(),
            "cliente_id"=>$request->get("cliente_id"),
            "posicao_x" => $request->get("p_d_x"),
            "posicao_y" => $request->get("p_d_y"),
            "km" => $km,
        ]);

        return redirect("/motorista/viagens/".$viagem->id);
    }

    function show(Request $request, $id){
        $viagem = Viagens::findOrFail($id);
        return view("motorista.viagens.show", ["viagem"=>$viagem]);
    }

    function alterarPreco(Request $request, $id){
        $viagem = Viagens::findOrFail($id);
        $request->validate([
            "preco"=>["required", new Dinheiro("Preço mal formatado!")]
        ]);
        $viagem->preco = $request->get("preco");
        $viagem->save();
        return back()->with("texto", "Preço actualizado!");
    }

    function marcarConcluido(Request $request, $id){
        $viagem = Viagens::findOrFail($id);
        $viagem->estado = 1;
        $viagem->save();
        $motorista = $viagem->motorista();
        $motorista->increment("km_realizado", $viagem->km);
        $motorista->save();
        return redirect("/motorista")->with("texto", "Viagem marcada como concluida!");
    }

    function atender(Request $request, $id){
        $viagem = Viagens::findOrFail($id);

        if($viagem->estado != Viagens::ESTADO_EM_ESPERA){
            return redirect("/motorista")->with("texto", "A viagem selecionada não está em espera!");
        }

        $viagem->estado = 0;
        $viagem->save();
        return redirect("/motorista/viagens/".$viagem->id);
    }

    function fila(){
        return view("motorista.viagens.fila");
    }

    function realizadas(){
        return view("motorista.viagens.realizadas");
    }
}
