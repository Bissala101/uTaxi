@extends("cliente.app", ["titulo"=>"Confirmar"])
@section("content")
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <?php
                 $carro = $motorista->carro();
                 ?>

                <div class="card">
                    <div class="card-body">
                        Motorista:<br />
                        <strong>{{$motorista->nome}}</strong><br />
                        Veículo:<br />
                        Tipo: <strong>{{$carro->tipo}}</strong><br />
                        Modelo: <strong>{{$carro->modelo}}</strong><br />
                        Preço base: <br />
                        <strong>{{\App\Helpers\Utils::formataDinheiro($preco)}}</strong>
                        Km: <br />
                        <strong>{{$km}}</strong>
                    </div>
                </div>

                <br />

                <form method="post" action="/viagens/publicar">
                    @foreach(request()->all() as $chave => $valor)
                        <input type="hidden" name="{{$chave}}" value="{{$valor}}">
                    @endforeach
                    <button class="btn btn-primary" type="submit">Confirmar</button> <a href="/" class="btn btn-danger">Cancelar</a>
                </form>

            </div>
        </div>
    </div>
@endsection
