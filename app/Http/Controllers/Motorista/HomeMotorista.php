<?php

namespace App\Http\Controllers\Motorista;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeMotorista extends Controller
{
    public function index()
    {
        return view("motorista.index");
    }

    function sair(){
        Auth::guard("motorista")->logout();
        return redirect("/motorista/login");
    }

    function mudar_estado(Request $request){
        $user = \auth()->guard("motorista")->user();
        $user->estado = $request->estado;
        $user->save();
        return redirect("/motorista")->with("texto", "Estado mudado!");
    }
}
