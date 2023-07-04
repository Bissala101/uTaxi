<!doctype html>
<html lang="pt_PT" data-bs-theme="auto">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Viagem activa</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <style>
        body{
            background: #eee;
        }
    </style>

</head>
<body>
<main class="">


    <div class="container" >
        <div class="row" style="height: 100vh; align-items: center; display: flex; justify-content: center;">
            <div class="col-md-5">

                @if($viagem->estado == 1)
                    <h5>Viagem concluída</h5>

                    <form method="post" action="/viagens/avaliar/{{$viagem->id}}" class="mb-3">
                        @csrf
                        @if (session()->has("texto"))
                            <div class="p-2">
                                <div class="alert alert-success">{{session("texto")}}</div>
                            </div>
                        @endif

                        @if ($errors->count() != 0)
                            <div class="p-2">
                                <div class="alert alert-danger">{{$errors->first()}}</div>
                            </div>
                        @endif

                        <p>Avalia este motorista com uma nota!</p>

                        <div class="form-group mb-3">
                            <label>Escolha uma nota de 1 a 100</label>
                            <select name="nota" class="form-select">
                                @for($i=1; $i<=100; $i++)
                                    <option value="{{$i}}">{{$i}}</option>
                                @endfor
                            </select>
                        </div>
                        <button class="btn btn-primary">Avaliar motorista</button>
                    </form>

                @endif

                @if($viagem->estado == 0)
                    <h5>Viagem activa</h5>
                @endif

                <div class="card">
                    <ul class="list-group list-group-flush">
                        <?php
                            $motorista = $viagem->motorista();
                            $carro = $motorista->carro();
                        ?>
                        <li class="list-group-item active">Informações do motorista</li>
                        <li class="list-group-item"><strong>Nome:</strong> {{$motorista->nome}}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{$motorista->email}}</li>
                        <li class="list-group-item active">Informações do veículo</li>
                        <li class="list-group-item"><strong>Tipo de veículo:</strong> {{$carro->tipo}}</li>
                        <li class="list-group-item"><strong>Modelo:</strong> {{$carro->modelo}}</li>
                        <li class="list-group-item"><strong>Velocidade média:</strong> {{$carro->velocidade_media}}</li>
                        <li class="list-group-item active">Informações da viagem</li>
                        <li class="list-group-item"><strong>Preço:</strong> {{\App\Helpers\Utils::formataDinheiro($viagem->preco)}}</li>
                        <li class="list-group-item"><strong>Posição y destino:</strong> {{$viagem->posicao_y}}</li>
                        <li class="list-group-item"><strong>Posição x destino:</strong> {{$viagem->posicao_x}}</li>
                        <li class="list-group-item"><strong>Km:</strong> {{$viagem->km}}</li>
                        <li class="list-group-item"><strong>Estado:</strong> A decorrer</li>
                    </ul>
                </div>
                <br />
            </div>
        </div>
    </div>

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
