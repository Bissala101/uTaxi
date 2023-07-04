<?php

namespace App\Http\Middleware;

use App\Models\Viagens;
use Closure;
use Illuminate\Http\Request;

class CheckViagemClienteMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if(auth()->check()) {
            $viagem = Viagens::where("estado", 0)->where("cliente_id", auth()->id())->first();
            if ($viagem) {
                return redirect("/viagens/" . $viagem->id);
            }
        }

        return $next($request);
    }
}
