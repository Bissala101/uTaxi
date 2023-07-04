<?php

namespace App\Http\Controllers\Cliente;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller
{
    function sair(){
        Auth::logout();
        return redirect("/login");
    }

    public function index()
    {
        return view("cliente.index");
    }
}
