<!doctype html>
<html lang="pt_PT" data-bs-theme="auto">
<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$titulo}}</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <style>

        body{
            background:#eee;
            height:100vh;
        }

        .bi {
            vertical-align: -.125em;
            fill: currentColor;
        }

        /*
         .list-unstyled > li > a,
         .list-unstyled > li > button {
             color: #ffffff;
         }

         */

         /*
        .btn-toggle::after {
            color: #ffffff !important;
        }

          */
    </style>

    <!-- Custom styles for this template -->
    <link href="{{asset("admin_assets/sidebars.css")}}" rel="stylesheet">
</head>
<body>
<main class="d-flex flex-nowrap">

    <div class="flex-shrink-0 p-3 shadow-lg" style="width: 280px; background: #fff;">
        <a href="/" class="d-flex align-items-center pb-3 mb-3 link-body-emphasis text-decoration-none border-bottom">
            <svg class="bi pe-none me-2" width="30" height="24"><use xlink:href="#bootstrap"/></svg>
            <span class="fs-5 fw-semibold">uTaxiAdmin</span>
        </a>
        <ul class="list-unstyled ps-0">

            <li class="mb-1">
                <a href="/admin" class="btn btn-toggle d-inline-flex align-items-center rounded border-0">
                    Home
                </a>
            </li>


            <li class="mb-1">
                <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#dashboard-collapse" aria-expanded="false">
                    Clientes
                </button>
                <div class="collapse" id="dashboard-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="/admin/clientes" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Visualizar</a></li>
                        <li><a href="/admin/clientes/add" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Adicionar</a></li>
                    </ul>
                </div>
            </li>

            <li class="mb-1">
                <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#orders-collapse" aria-expanded="false">
                    Motoristas
                </button>
                <div class="collapse" id="orders-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="/admin/motoristas" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Visualizar</a></li>
                        <li><a href="/admin/motoristas/add" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Adicionar</a></li>
                    </ul>
                </div>
            </li>

            <li class="mb-1">
                <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#carros-collapse" aria-expanded="false">
                    Carros
                </button>
                <div class="collapse" id="carros-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="/admin/carros" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Visualizar</a></li>
                        <li><a href="/admin/carros/add" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Adicionar</a></li>
                        <li><a href="/admin/carros/associar" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Associar com motorista</a></li>
                    </ul>
                </div>
            </li>

            <li class="mb-1">
                <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#rel-collapse" aria-expanded="false">
                    Relatórios
                </button>
                <div class="collapse" id="rel-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="/admin/relatorios/clientes" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Cliente</a></li>
                        <li><a href="/admin/relatorios/motoristas" class="link-body-emphasis d-inline-flex text-decoration-none rounded">Motorista</a></li>
                    </ul>
                </div>
            </li>

            <li class="border-top my-3"></li>
            <li class="mb-1">
                <button class="btn btn-toggle d-inline-flex align-items-center rounded border-0 collapsed" data-bs-toggle="collapse" data-bs-target="#account-collapse" aria-expanded="false">
                    Conta
                </button>
                <div class="collapse" id="account-collapse">
                    <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small">
                        <li><a href="/admin/sair?token={{csrf_token()}}" class="link-dark d-inline-flex text-decoration-none rounded">Terminar sessão</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>


    <div class="w-100">
        @yield("content")
    </div>



</main>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4" crossorigin="anonymous"></script>
</body>
</html>
