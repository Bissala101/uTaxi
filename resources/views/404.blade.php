<!DOCTYPE html>
<html lang="pt_PT">


<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>{{$codigo}}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>


<body>
<div class="d-flex align-items-center justify-content-center vh-100">
    <div class="text-center">
        <h1 class="display-1 fw-bold">{{$codigo}}</h1>
        <p class="fs-3">{{$titulo}}</p>
        <p class="lead">
            {{$texto}}
        </p>
        <a href="/" class="btn btn-primary">Home</a>
    </div>
</div>
</body>


</html>
