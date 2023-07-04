<?php

namespace App\Http\Middleware;

use App\Models\Viagens;
use Closure;
use Illuminate\Http\Request;

class CheckViagemMotoristaMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(auth()->guard("motorista")->check()) {
            $viagem = Viagens::where("estado", 0)->where("motorista_id", auth()->guard("motorista")->id())->first();
            if ($viagem) {
                return redirect("/motorista/viagens/" . $viagem->id);
            }
        }

        return $next($request);
    }
}
