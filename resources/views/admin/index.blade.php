@extends("admin.app", ["titulo"=>"uTaxi Admin"])

<?php
    $total_viagens = \App\Models\Viagens::where("estado", 1)->count();
    $total_akz = \App\Models\Viagens::where("estado", 1)->sum("preco");
    $clientes = \App\Models\User::count();
    $motoristas = \App\Models\Motoristas::count();

$usuariosMaisGastam = \App\Models\User::join('viagens', 'users.id', '=', 'viagens.cliente_id')
    ->select('users.nome', \Illuminate\Support\Facades\DB::raw('SUM(viagens.preco) as total_gasto'))
    ->groupBy('users.id', 'users.nome')
    ->orderByDesc('total_gasto')
    ->limit(10)
    ->get();

$motoristasMaisGastam = \App\Models\Motoristas::join('viagens', 'motorista.id', '=', 'viagens.motorista_id')
    ->select('motorista.nome', \Illuminate\Support\Facades\DB::raw('SUM(viagens.preco) as total_gasto'))
    ->groupBy('motorista.id', 'motorista.nome')
    ->orderByDesc('total_gasto')
    ->limit(10)
    ->get();
?>

@section("content")
    <div class="container">
        <div class="row mt-5">

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="p-3">
                        <h2 class="text-center">{{\App\Helpers\Utils::formataDinheiro($total_akz)}}</h2>
                        <div class="text-center">Total de lucros</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="p-3">
                        <h2 class="text-center">{{$total_viagens}}</h2>
                        <div class="text-center">Viagens realizadas</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="p-3">
                        <h2 class="text-center">{{$motoristas}}</h2>
                        <div class="text-center">Total de motoristas</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="p-3">
                        <h2 class="text-center">{{$clientes}}</h2>
                        <div class="text-center">Total de clientes</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-md-12"><h2>Relatórios</h2></div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="m-0">Os 10 clientes que mais gastam</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($usuariosMaisGastam as $usuario)
                            <li class="list-group-item"><strong>{{$usuario->nome}}</strong>: {{\App\Helpers\Utils::formataDinheiro($usuario->total_gasto)}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card border-0 shadow-sm">
                    <div class="card-header">
                        <h5 class="m-0">Os 5 motoristas com mais lucros</h5>
                    </div>
                    <ul class="list-group list-group-flush">
                        @foreach($motoristasMaisGastam as $motorista)
                            <li class="list-group-item"><strong>{{$motorista->nome}}</strong>: {{\App\Helpers\Utils::formataDinheiro($motorista->total_gasto)}}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>
    </div>
@endsection
