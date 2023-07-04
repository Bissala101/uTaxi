<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        return view("admin.index");
    }

    function sair(){
        Auth::guard("admin")->logout();
        return redirect("/admin/login");
    }

    function relatorio_clientes(){
        return view("admin.relatorios.clientes");
    }

    function relatorio_motoristas(){
        return view("admin.relatorios.motoristas");
    }
}
