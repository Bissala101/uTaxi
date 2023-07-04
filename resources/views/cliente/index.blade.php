@extends("cliente.app", ["titulo"=>"uTaxi Cliente"])


@section("content")
    <div class="container mt-5">

        @if (session()->has("texto"))
            <div class="p-3">
                <div class="alert alert-success">{{session("texto")}}</div>
            </div>
        @endif

        <div class="row justify-content-center">

            <div class="col-md-4">
                <h2>Opções</h2>
                <ul class="list-group">
                    <a href="/nova_viagem" class="list-group-item list-group-item-action">Nova viagem</a>
                    <a href="/viagens/fila" class="list-group-item list-group-item-action">Viagens em espera</a>
                    <a href="/viagens/realizadas" class="list-group-item list-group-item-action">Viagens realizadas</a>
                    <a href="/sair?token={{csrf_token()}}" class="list-group-item list-group-item-action">Terminar sessão</a>
                </ul>
            </div>

            <div class="col-md-6">
                <h2>Viagens recentes</h2>
                <?php
                $viagens = \App\Models\Viagens::where("cliente_id", auth()->id())->where("estado", \App\Models\Viagens::ESTADO_CONCLUIDO)->limit(4)->get();
                ?>
                @foreach($viagens as $v)

                        <?php
                        $motorista = $v->motorista();
                        $cliente = $v->cliente();
                        $carro = $motorista->carro();
                        ?>

                    <div class="card ">
                        <div class="card-body">
                            <h5>Codigo da viagem <strong>#{{$v->id}}</strong></h5><br />

                            <strong>Motorista:</strong> {{$motorista->nome}}<br />
                            <strong>Email:</strong> {{$motorista->email}}<br />
                            <hr />
                            <strong>Modelo do veículo:</strong> {{$carro->modelo}}<br />
                            <strong>Tipo de veículo:</strong> {{$carro->tipo}}<br />
                            <strong>Velocidade média do veículo:</strong> {{$carro->velocidade_media}}<br />
                            <hr />
                            <strong>Total pago:</strong> {{\App\Helpers\Utils::formataDinheiro($v->preco)}}<br />
                            <strong>Estado:</strong> <span class="text-success">Concluído</span><br />
                        </div>
                    </div>
                    <br />
                @endforeach
                @if($viagens->count() == 0)
                    <div class="p-5"><h5 class="text-center text-danger">Nenhuma viagem</h5></div>
                @endif

                @if($viagens->count() != 0)
                    <a href="/motorista/viagens/realizadas" class="btn btn-primary">Ver todas</a>
                @endif
            </div>

        </div>

    </div>
@endsection
