@extends("motorista.app", ["titulo"=>"Nova viagem"])
@section("content")
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">


                <div class="card">
                    <div class="card-body">
                        Cliente: <strong>{{$cliente->nome}}</strong><br />
                        Email: <strong>{{$cliente->email}}</strong><br />
                        Preço: <strong>{{\App\Helpers\Utils::formataDinheiro($preco)}}</strong><br />
                        Km: <strong>{{$km}}</strong><br />
                    </div>
                </div>

                <br />

                <form method="post" action="/motorista/viagens/publicar">
                    @foreach(request()->all() as $chave => $valor)
                        <input type="hidden" name="{{$chave}}" value="{{$valor}}">
                    @endforeach
                    <button class="btn btn-primary" type="submit">Confirmar</button>  <a href="/motorista" class="btn btn-danger">Cancelar</a>
                </form>

            </div>
        </div>
    </div>
@endsection
