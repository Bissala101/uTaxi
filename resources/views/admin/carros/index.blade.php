@extends("admin.app", ["titulo"=>"Veículos"])


@section("content")
    <div class="container mt-5">

        <?php
            $clientes = \App\Models\Carros::orderBy("id", "DESC");
            $clientes = $clientes->paginate(10, ["*"], "p");
        ?>

        <di class="row">
            <div class="col-md-12">

                @if (session()->has("texto"))
                    <div class="p-3">
                        <div class="alert alert-success">{{session("texto")}}</div>
                    </div>
                @endif
                <br />


                <a href="/admin/carros/add" class="btn btn-primary mb-3">Adicionar veículo</a>

                <table class="table table-striped" style="background: #fff">
                    <thead>
                    <tr>
                        <th>Nome</th>
                        <th scope="col">Modelo</th>
                        <th scope="col">Tipo</th>
                        <th scope="col">Velocidade Média</th>
                        <th scope="col">Preço</th>
                        <th scope="col">Fiabilidade</th>
                        <th scope="col">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clientes as $c)
                        <tr>
                            <th>{{$c->nome}}</th>
                            <th>{{$c->modelo}}</th>
                            <th>{{$c->tipo}}</th>
                            <td>{{$c->velocidade_media}}</td>
                            <td>{{\App\Helpers\Utils::formataDinheiro($c->preco)}}</td>
                            <td>{{$c->fiabilidade}}</td>
                            <td>
                                <a href="/admin/carros/editar/{{$c->id}}" class="btn btn-primary">Editar</a>
                                <a href="/admin/carros/eliminar/{{$c->id}}?token={{csrf_token()}}" data-confirm="Tens a certeza que quer eliminar este veículo?" class="delete btn btn-danger">Eliminar</a>
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
