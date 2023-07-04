@extends("cliente.app", ["titulo"=>"Motoristas próximos"])
@section("content")
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <h2 class="mb-5">Escolher motoristas proximos</h2>
                <?php
                    $motoristas = \App\Models\Motoristas::orderBy("id", "desc")->whereExists(function($query){
                        $query->from("carros")->whereRaw("carros.motorista_id = motorista.id");
                    })->get();
                ?>

                @foreach($motoristas as $motorista)

                    <?php
                        $carro = $motorista->carro();
                    ?>

                    <div class="card">
                        <div class="card-body">
                            Nome: <strong>{{$motorista->nome}}</strong><br />
                            Tipo: <strong>{{$carro->tipo}}</strong><br />
                            Modelo: <strong>{{$carro->modelo}}</strong><br />
                            Preço: <strong>{{\App\Helpers\Utils::formataDinheiro($carro->preco)}}</strong><br />


                            <form class="mt-3" method="post" action="/viagens/confirmar">
                                @foreach(request()->except(["motorista_id"]) as $chave => $valor)
                                    <input type="hidden" name="{{$chave}}" value="{{$valor}}">
                                @endforeach
                                <input type="hidden" name="motorista_id" value="{{$motorista->id}}">
                                <button class="btn btn-primary" type="submit">Escolher</button>
                            </form>
                        </div>
                    </div>
                    <br />

                @endforeach
                <br />
            </div>
        </div>
    </div>
@endsection
