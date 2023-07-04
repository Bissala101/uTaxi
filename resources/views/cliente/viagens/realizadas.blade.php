@extends("cliente.app", ["titulo"=>"Viagens realizadas"])


@section("content")
    <div class="container mt-5">

        @if (session()->has("texto"))
            <div class="p-3">
                <div class="alert alert-success">{{session("texto")}}</div>
            </div>
        @endif

        <div class="row justify-content-center">


            <div class="col-md-6">
                <h2 class="mb-5">Viagens realizadas</h2>
                <?php
                $viagens = \App\Models\Viagens::where("cliente_id", auth()->id())->where("estado", \App\Models\Viagens::ESTADO_CONCLUIDO)->get();
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
                            <strong>Estado:</strong> <span class="text-sucess">Concluído</span><br />
                        </div>
                    </div>
                    <br />
                @endforeach
                @if($viagens->count() == 0)
                    <div class="p-5"><h5 class="text-center text-danger">Nenhuma viagem</h5></div>
                @endif
            </div>

        </div>

    </div>
@endsection
