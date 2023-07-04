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
                <h5>Viagem activa</h5>
                <div class="card">
                    <ul class="list-group list-group-flush">
                        <?php
                            $motorista = $viagem->motorista();
                            $carro = $motorista->carro();
                            $cliente = $viagem->cliente();
                        ?>
                        <li class="list-group-item active">Informações do cliente</li>
                        <li class="list-group-item"><strong>Cliente:</strong> {{$cliente->nome}}</li>
                        <li class="list-group-item"><strong>Email:</strong> {{$cliente->email}}</li>
                        <li class="list-group-item active">Informações do veículo</li>
                        <li class="list-group-item"><strong>Tipo de veículo:</strong> {{$carro->tipo}}</li>
                        <li class="list-group-item"><strong>Modelo:</strong> {{$carro->modelo}}</li>
                        <li class="list-group-item"><strong>Velocidade média:</strong> {{$carro->velocidade_media}}</li>
                        <li class="list-group-item active">Informações da viagem</li>
                        <li class="list-group-item"><strong>Preço base:</strong> {{\App\Helpers\Utils::formataDinheiro($viagem->preco)}}</li>
                        <li class="list-group-item"><strong>Posição y destino:</strong> {{$viagem->posicao_y}}</li>
                        <li class="list-group-item"><strong>Posição x destino:</strong> {{$viagem->posicao_x}}</li>
                        <li class="list-group-item"><strong>Km:</strong> {{$viagem->km}}</li>
                        <li class="list-group-item"><strong>Estado:</strong> A decorrer</li>
                    </ul>
                </div>
                <br />


                <form method="post" action="/motorista/viagens/mudar_preco/{{$viagem->id}}" class="mb-3">
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

                    <label>Preço actual</label>
                    <div class="form-group mb-3">
                    <input required value="{{$viagem->preco}}"  class="form-control" placeholder="Preço actual" type="number" name="preco" />
                    </div>
                    <button class="btn btn-primary">Actualizar o preço</button>
                </form>

                <hr />
                <button type="button" class="btn btn-success" id="marcarConcluido">Marcar como concluído</button>
            </div>
        </div>
    </div>


    <script>

            document.getElementById('marcarConcluido').addEventListener('click', function() {
            if (confirm('Tem certeza de que deseja marcar como concluído?')) {
            // Criar um formulário dinamicamente
            var form = document.createElement('form');
            form.method = 'POST';
            form.action = '/motorista/viagens/concluido/{{$viagem->id}}'; // Substitua pelo seu URL real

            // Adicionar um campo oculto para o método POST
            var token = document.createElement('input');
            token.setAttribute('type', 'hidden');
            token.setAttribute('name', '_token');
            token.setAttribute('value', '{{ csrf_token() }}'); // Adicione isso caso esteja usando o Laravel

            // Adicionar o campo oculto ao formulário
            form.appendChild(token);

            // Adicionar o formulário à página e submetê-lo
            document.body.appendChild(form);
            form.submit();
        }
        });

    </script>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
