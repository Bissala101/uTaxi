<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Carros;
use App\Models\Motoristas;
use App\Models\User;
use App\Rules\Dinheiro;
use Illuminate\Http\Request;

class CarrosController extends Controller
{
    function index(){
        return view("admin.carros.index");
    }

    function store(Request $request){

        $request->validate([
            "nome"=>["required"],
            "modelo"=>["required"],
            "preco"=>["required", new Dinheiro("O preço esta mal formatado")],
            "tipo"=>["required"],
            "velocidade_media"=>["required", "integer"],
            "fiabilidade"=>["required", "integer"],
            "p_y"=>["required", "integer"],
            "p_x"=>["required", "integer"],
        ],[
            "nome.required"=>"Por favor digita o nome",
            "modelo.required"=>"Por favor digita o modelo",
            "tipo.required"=>"Por favor escolhe o tipo de veículo",
            "velocidade_media.required"=>"Por favor introduz a velocidade média",
            "p_y.required"=>"Por favor introduz a posição y",
            "p_x.required"=>"Por favor introduz a posição x",
            "p_y.integer"=>"A posição y tem de ser um número",
            "p_x.integer"=>"A posição x tem de ser um número",
            "velocidade_media.integer"=>"A velocidade média tem de ser um número"
        ]);


        Carros::create([
            "nome"=>$request->get("nome"),
            "modelo"=>$request->get("modelo"),
            "preco"=>$request->get("preco"),
            "tipo" => $request->get("tipo"),
            "velocidade_media" => $request->get("velocidade_media"),
            "fiabilidade"=>$request->get("fiabilidade"),
            "posicao_x" => $request->get("p_x"),
            "posicao_y" => $request->get("p_y"),
            "motorista_id" => 0,
        ]);

        return redirect("/admin/carros")->with("texto", "Veículo adicionado!");
    }

    function add(){
        return view("admin.carros.add");
    }

    function update(Request $request, $id){
        $item = Carros::findOrFail($id);
        return view("admin.carros.editar", ["item"=>$item]);
    }

    function actualizar(Request $request, $id){
        $item = Carros::findOrFail($id);

        $request->validate([
            "nome"=>["required"],
            "modelo"=>["required"],
            "preco"=>["required", new Dinheiro("O preço esta mal formatado")],
            "tipo"=>["required"],
            "velocidade_media"=>["required", "integer"],
            "fiabilidade"=>["required", "integer"],
            "p_y"=>["required", "integer"],
            "p_x"=>["required", "integer"],
        ],[
            "nome.required"=>"Por favor digita o nome",
            "modelo.required"=>"Por favor digita o modelo",
            "tipo.required"=>"Por favor escolhe o tipo de veículo",
            "velocidade_media.required"=>"Por favor introduz a velocidade média",
            "p_y.required"=>"Por favor introduz a posição y",
            "p_x.required"=>"Por favor introduz a posição x",
            "p_y.integer"=>"A posição y tem de ser um número",
            "p_x.integer"=>"A posição x tem de ser um número",
            "velocidade_media.integer"=>"A velocidade média tem de ser um número"
        ]);



        $item->nome = $request->get("nome");
        $item->modelo = $request->get("modelo");
        $item->tipo = $request->get("tipo");
        $item->velocidade_media = $request->get("velocidade_media");
        $item->preco = $request->get("preco");
        $item->posicao_y = $request->get("p_y");
        $item->posicao_x = $request->get("p_x");
        $item->fiabilidade = $request->get("fiabilidade");
        $item->save();

        return redirect("/admin/carros")->with("texto", "Veículo actualizado!");
    }

    function destroy(Request $request, $id){
        $item = User::findOrFail($id);

        if($request->token == csrf_token()) {
            $item->delete();
            return redirect("/admin/carros")->with("texto", "Veículo eliminado!");
        }

        return back();
    }

    function associar(){
        return view("admin.carros.associar");
    }

    function associarAdd(Request $request){

        $request->validate([
            "carro_id"=>["required","exists:carros,id"],
            "motorista_id"=>["required","exists:motorista,id"],
        ],[
            "carro_id.required"=>"Por favor seleciona um carro",
            "motorista_id.required"=>"Por favor seleciona um motorista",
        ]);

        $carro = Carros::find($request->get("carro_id"));

        if($carro->motorista_id){
            return redirect("/admin/carros/associar")->withErrors(["texto"=>"Este carro já tem um motorista associado!"]);
        }

        $carro->motorista_id = $request->get("motorista_id");
        $carro->save();

        return back()->with("texto", "Associação efectuada com sucesso!");
    }

    function eliminarAssociao(Request $request, $id){
        $item = Carros::findOrFail($id);
        $item->motorista_id = 0;
        $item->save();
        return back()->with("texto", "Associação eliminada");
    }
}
