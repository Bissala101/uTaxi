<?php

namespace App\Http\Controllers\Cliente;

use App\Helpers\Utils;
use App\Http\Controllers\Controller;
use App\Models\Motoristas;
use App\Models\Viagens;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ViagensController extends Controller
{
    public function index()
    {

    }

    function nova_viagem(){
        return view("cliente.viagens.nova_viagem");
    }

    function processViagem(Request $request){

        $request->validate([
            "p_y"=>["required", "integer"],
            "p_x"=>["required", "integer"],
            "p_d_y"=>["required", "integer"],
            "p_d_x"=>["required", "integer"],
            "motorista_id"=>["required"],
        ]);

        if($request->get("motorista_id") == 0){
            return view("cliente.viagens.motoristas_proximos");
        } else {

            $motorista = Motoristas::findOrFail($request->get("motorista_id"));
            $carro = $motorista->carro();

            $km = Utils::calcularDistancia($request->get("p_d_x"), $request->get("p_d_y"), $motorista->posicao_x, $motorista->posicao_y);
            $preco = $carro->preco * $km;

            return view("cliente.viagens.confirmar_viagem", ["motorista"=>$motorista, "km"=>$km, "preco"=>$preco]);
        }
    }

    function viagemConfirmar(Request $request){
        $motorista = Motoristas::findOrFail($request->get("motorista_id"));
        $carro = $motorista->carro();


        $request->validate([
            "p_y"=>["required", "integer"],
            "p_x"=>["required", "integer"],
            "p_d_y"=>["required", "integer"],
            "p_d_x"=>["required", "integer"],
        ]);

        
        $km = Utils::calcularDistancia($request->get("p_d_x"), $request->get("p_d_y"), $motorista->posicao_x, $motorista->posicao_y);
        $preco = $carro->preco * $km;

        return view("cliente.viagens.confirmar_viagem", ["motorista"=>$motorista, "km"=>$km, "preco"=>$preco]);
    }

    function publicarViagem(Request $request){

        $request->validate([
            "p_y"=>["required", "integer"],
            "p_x"=>["required", "integer"],
            "p_d_y"=>["required", "integer"],
            "p_d_x"=>["required", "integer"],
            "motorista_id"=>["required", "exists:motorista,id"],
        ]);

        $motorista = Motoristas::findOrFail($request->get("motorista_id"));
        $carro = $motorista->carro();

        $estado = 0;

        $viagem_activa =  Viagens::where("motorista_id", $motorista->id)->where("estado", Viagens::ESTADO_ACTVIO)->first();
        if($viagem_activa){
            $estado = Viagens::ESTADO_EM_ESPERA;
        }

        if($motorista->estado == 1){
            $estado = Viagens::ESTADO_EM_ESPERA;
        }

        $km = Utils::calcularDistancia($request->get("p_d_x"), $request->get("p_d_y"), $motorista->posicao_x, $motorista->posicao_y);
        $preco = $carro->preco * $km;

        $viagem = Viagens::create([
            "preco" => $preco,
            "motorista_id" => $motorista->id,
            "cliente_id"=>Auth::id(),
            "posicao_x" => $request->get("p_d_x"),
            "posicao_y" => $request->get("p_d_y"),
            "km" => $km,
            "estado"=>$estado,
        ]);

        if($estado == Viagens::ESTADO_EM_ESPERA){
            return redirect("/")->with("texto", "No momento o motorista está ausente ou ocupado, a viagem foi colocada na lista de espera!");
        } else {
            return redirect("/viagens/".$viagem->id);
        }
    }

    function classifiar_motorista(Request $request, $id){
        $viagem = Viagens::findOrFail($id);
        $request->validate([
            "nota"=>["required", "integer"],
        ]);
        $motorista = $viagem->motorista();
        $motorista->increment("classificacao", $request->nota);
        return redirect("/")->with("texto", "Motorista avaliado!");
    }

    function fila(){
        return view("cliente.viagens.fila");
    }

    function realizadas(){
        return view("cliente.viagens.realizadas");
    }

    function show(Request $request, $id){
        $viagem = Viagens::findOrFail($id);
        return view("cliente.viagens.show", ["viagem"=>$viagem]);
    }
}
