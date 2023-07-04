<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class ClientesController extends Controller
{
    function index(){
        return view("admin.clientes.index");
    }

    function store(Request $request){
        $request->validate([
            "nome"=>["required", "max:255"],
            "email"=>["required", "email", "unique:users"],
            "password"=>["required"],
            "morada"=>["required"],
            "data_nascimento"=>["required", "date"],
        ],[
            "nome.required"=>"Por favor digita o nome",
            "nome.max"=>"Nome muito grande",
            "email.required"=>"Por favor introduz um email",
            "email.email"=>"Por favor introduz um email válido",
            "password.required"=>"Por favor introduz uma palavra passe",
            "morada.required"=>"Por favor introduz uma morada",
            "data_nascimento.required"=>"Por favor introduz a data de aniversário",
            "data_nascimento.date"=>"Por favor introduz uma data de aniversário válida",
            "email.unique"=>"Já existe um cliente com este email",
        ]);


        $date = Carbon::make($request->get("data_nascimento"));

        User::create([
            "nome" => $request->get("nome"),
            "email" => $request->get("email"),
            "morada" => $request->get("morada"),
            "password" => Hash::make($request->get("password")),
            "posicao_x" => 1,
            "posicao_y" => 1,
            "data_nascimento" => $date,
        ]);

        return redirect("/admin/clientes")->with("texto", "Cliente adicionado!");
    }

    function add(){
        return view("admin.clientes.add");
    }

    function update(Request $request, $id){
        $item = User::findOrFail($id);
        return view("admin.clientes.editar", ["item"=>$item]);
    }

    function actualizar(Request $request, $id){
        $item = User::findOrFail($id);

        $request->validate([
            "nome"=>["required", "max:255"],
            "email"=>["required", "email", Rule::unique("users")->ignore($item->id)],
            "morada"=>["required"],
            "data_nascimento"=>["required", "date"],
        ],[
            "nome.required"=>"Por favor digita o nome",
            "nome.max"=>"Nome muito grande",
            "email.required"=>"Por favor introduz um email",
            "email.email"=>"Por favor introduz um email válido",
            "morada.required"=>"Por favor introduz uma morada",
            "data_nascimento.required"=>"Por favor introduz a data de aniversário",
            "data_nascimento.date"=>"Por favor introduz uma data de aniversário válida",
            "email.unique"=>"Já existe um cliente com este email",
        ]);


        $date = Carbon::make($request->get("data_nascimento"));

        $item->nome = $request->get("nome");
        $item->email = $request->get("email");
        $item->morada = $request->get("morada");
        $item->data_nascimento = $date;

        if($request->get("password")) {
            $item->password = $request->get("password");
        }

        $item->save();

        return redirect("/admin/clientes")->with("texto", "Cliente actualizado!");
    }

    function destroy(Request $request, $id){
        $item = User::findOrFail($id);

        if($request->token == csrf_token()) {
            $item->delete();
            return redirect("/admin/clientes")->with("texto", "Cliente eliminado!");
        }

        return back();
    }
}
