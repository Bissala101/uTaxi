@extends("admin.app", ["titulo"=>"Veículos"])


@section("content")
    <div class="container mt-5">

        <?php
        $clientes = \App\Models\Carros::orderBy("id", "DESC")->where("motorista_id", "!=", 0);
        $clientes = $clientes->paginate(10, ["*"], "p");

        $carros = \App\Models\Carros::orderBy("id", "DESC")->where("motorista_id", "=", 0)->get();

        $motoristas = \App\Models\Motoristas::orderBy("id", "DESC")->whereNotExists(function($query){
            $query->from("carros")->whereRaw("carros.motorista_id = motorista.id");
        })->get();
        ?>

        <di class="row">
            <div class="col-md-4">
                <form method="post" action="/admin/carros/associar/add">
                    @csrf
                    @if ($errors->count() != 0)
                        <div class="p-3">
                            <div class="alert alert-danger">{{$errors->first()}}</div>
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <label>Veículo</label>
                        <select name="carro_id" class="form-select">
                            @foreach($carros as $c)
                                <option value="{{$c->id}}">{{$c->nome}} - {{$c->modelo}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <label>Motorista</label>
                        <select name="motorista_id" class="form-select">
                            @foreach($motoristas as $m)
                                <option value="{{$m->id}}">{{$m->nome}}</option>
                            @endforeach
                        </select>
                    </div>

                    <button class="btn btn-primary">Associar</button>
                </form>
            </div>
            <div class="col-md-8">

                @if (session()->has("texto"))
                    <div class="p-3">
                        <div class="alert alert-success">{{session("texto")}}</div>
                    </div>
                @endif
                <br />


                <table class="table table-striped" style="background: #fff">
                    <thead>
                    <tr>
                        <th scope="col">Motorista</th>
                        <th scope="col">Veículo</th>
                        <th scope="col">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clientes as $c)

                        <tr>
                            <th>{{$c->motorista()->nome}}</th>
                            <td>{{$c->nome}} - {{$c->modelo}}</td>
                            <td>
                                <a href="/admin/carros/associar/eliminar/{{$c->id}}?token={{csrf_token()}}" data-confirm="Tens a certeza que quer eliminar esta associação?" class="delete btn btn-danger">Eliminar</a>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </di>

    </div>

    <script>

        const deleteLinks = document.querySelectorAll('.delete');

        for (let i = 0; i < deleteLinks.length; i++) {
            deleteLinks[i].addEventListener('click', function(event) {
                event.preventDefault();

                const choice = confirm(this.getAttribute('data-confirm'));

                if (choice) {
                    window.location.href = this.getAttribute('href');
                }
            });
        }

    </script>
@endsection
