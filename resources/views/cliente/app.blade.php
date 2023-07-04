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
    <div class="container">

        <?php
           $path = request()->path();
        ?>

        <header class="d-flex flex-wrap justify-content-center shadow-sm bg-white py-3 mb-4 border-bottom">
            <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
                <span class="fs-4" style="padding-left: 20px;">uTaxi</span>
            </a>
            <ul class="nav nav-pills">
                <li class="nav-item"><a href="/" class="nav-link {{$path == "/" ? "active":""}}" aria-current="page">Home</a></li>
                <li class="nav-item"><a href="/nova_viagem" class="nav-link {{$path == "nova_viagem" ? "active":""}}">Nova viagem</a></li>
                <li class="nav-item"><a href="/viagens/fila" class="nav-link {{$path == "viagens/fila" ? "active":""}}">Viagens em espera</a></li>
                <li class="nav-item"><a href="/viagens/realizadas" class="nav-link {{$path == "viagens/realizadas" ? "active":""}}">Viagens realizadas</a></li>
                <li class="nav-item"><a href="/sair?token={{csrf_token()}}" class="nav-link">Terminar sessão</a></li>
            </ul>
        </header>
    </div>

    <div class="w-100 mb-5">
        @yield("content")
    </div>

</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
