<!doctype html>
<html lang="pt_PT" data-bs-theme="auto">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$titulo}}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <style>
        body{
            background: #eee;
        }
    </style>

</head>
<body>
<main class="">

    <?php
        $user = auth()->guard("motorista")->user();
    ?>


    <div class="container mt-5 mb-5">
        @if (session()->has("texto"))
            <div class="alert alert-success mt-2">{{session("texto")}}</div>
        @endif

        <div class="mb-2">Olá <strong>{{auth()->guard("motorista")->user()->nome}}</strong></div>
        <div class="row justify-content-center">
            <div class="col-md-4">

                <ul class="list-group">
                    <li class="list-group-item active">Opções</li>
                    <a href="/motorista" class="list-group-item list-group-item-action">Resumo</a>
                    <a href="/motorista/viagens/fila" class="list-group-item list-group-item-action">Lista de espera</a>
                    <a href="/motorista/nova_viagem" class="list-group-item list-group-item-action">Nova viagem</a>
                    <a href="/motorista/viagens/realizadas" class="list-group-item list-group-item-action">Viagens realizadas</a>
                    <a href="/motorista/sair?token={{csrf_token()}}" class="list-group-item list-group-item-action">Terminar sessão</a>
                </ul>

                <form method="post" action="/motorista/mudar_estado">
                    @csrf
                    <div class="form-group mt-3">
                        <label>Estado</label>
                        <div class="input-group">
                            <select class="form-select" name="estado">
                                <option {{$user->estado == 0 ? "selected":""}} value="0">Activo</option>
                                <option {{$user->estado == 1 ? "selected":""}} value="1">Ausente</option>
                            </select>
                            <button class="btn btn-success">Actualizar</button>
                        </div>
                    </div>
                </form>

            </div>

            <div class="col-md-8">
                <div class="w-100">
                    @yield("content")
                </div>
            </div>
        </div>
    </div>
</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
