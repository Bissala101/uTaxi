<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Motoristas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    function loginAdmin(Request $request){

        $request->validate([
            "email" => 'required',
            'password' => 'required',
        ],[
            "email.required"=>"Por favor digita seu número de telefone",
            "password.required"=>"Por favor digita a tua palavra passe",
        ]);

        $user = Admin::where("email", $request->email)->first();

        if(!$user){
            return redirect()->back()->withErrors(["email"=>"Não conseguimos reconhecer o email introduzido!"])->withInput();
        }

        if(!Hash::check($user->password, $request->password)){
            Auth::guard("admin")->login($user, $request->remember ? true:false);
            return redirect("/admin");
        }

        return redirect()->back()->withErrors([
            'id' => 'A palavra passe está errada',
        ])->withInput();
    }

    function loginAdminView(){
        return view("admin.login");
    }

    function loginCliente(Request $request){

        $request->validate([
            "email" => 'required',
            'password' => 'required',
        ],[
            "email.required"=>"Por favor digita seu número de telefone",
            "password.required"=>"Por favor digita a tua palavra passe",
        ]);

        $user = User::where("email", $request->email)->first();

        if(!$user){
            return redirect()->back()->withErrors(["email"=>"Não conseguimos reconhecer o email introduzido!"])->withInput();
        }

        if(!Hash::check($user->password, $request->password)){
            Auth::login($user, $request->remember ? true:false);
            return redirect("/");
        }

        return redirect()->back()->withErrors([
            'id' => 'A palavra passe está errada',
        ])->withInput();
    }

    function loginMotorista(Request $request){

        $request->validate([
            "email" => 'required',
            'password' => 'required',
        ],[
            "email.required"=>"Por favor digita seu número de telefone",
            "password.required"=>"Por favor digita a tua palavra passe",
        ]);

        $user = Motoristas::where("email", $request->email)->first();

        if(!$user){
            return redirect()->back()->withErrors(["email"=>"Não conseguimos reconhecer o email introduzido!"])->withInput();
        }

        if(!Hash::check($user->password, $request->password)){
            Auth::guard("motorista")->login($user, (bool)$request->remember);
            return redirect("/motorista");
        }

        return redirect()->back()->withErrors([
            'id' => 'A palavra passe está errada',
        ])->withInput();
    }

    function loginClienteView(){
        return view("cliente.login");
    }

    function loginMotoristaView(){
        return view("motorista.login");
    }

    function registerView(){
        return view("cliente.register");
    }

    function registerCliente(Request $request){

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

        $user = User::create([
            "nome" => $request->get("nome"),
            "email" => $request->get("email"),
            "morada" => $request->get("morada"),
            "password" => Hash::make($request->get("password")),
            "posicao_x" => 1,
            "posicao_y" => 1,
            "data_nascimento" => $date,
        ]);

        Auth::login($user);

        return redirect("/")->with("texto", "Conta criada com sucesso!");
    }

    function registerMotoristaView(){
        return view("motorista.register");
    }

    function registerMotorista(Request $request){

        $request->validate([
            "nome"=>["required", "max:255"],
            "email"=>["required", "email", "unique:motorista"],
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

        $user = Motoristas::create([
            "nome" => $request->get("nome"),
            "email" => $request->get("email"),
            "morada" => $request->get("morada"),
            "password" => Hash::make($request->get("password")),
            "posicao_x" => 1,
            "posicao_y" => 1,
            "data_nascimento" => $date,
        ]);

        Auth::guard("motorista")->login($user);
        return redirect("/motorista")->with("texto", "Conta criada com sucesso!");
    }
}
