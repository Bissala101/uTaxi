@extends("admin.app", ["titulo"=>"Clientes"])


@section("content")
    <div class="container mt-5">

        <?php
            $clientes = \App\Models\User::orderBy("id", "DESC");
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


                <a href="/admin/clientes/add" class="btn btn-primary mb-3">Adicionar cliente</a>

                <table class="table table-striped" style="background: #fff">
                    <thead>
                    <tr>
                        <th scope="col">Nome</th>
                        <th scope="col">Email</th>
                        <th scope="col">Morada</th>
                        <th scope="col">Data de nascimento</th>
                        <th scope="col">Ações</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($clientes as $c)
                        <tr>
                            <th>{{$c->nome}}</th>
                            <td>{{$c->email}}</td>
                            <td>{{$c->morada}}</td>
                            <td>{{\Carbon\Carbon::make($c->data_nascimento)->format("d/m/Y")}}</td>
                            <td>
                                <a href="/admin/clientes/editar/{{$c->id}}" class="btn btn-primary">Editar</a>
                                <a href="/admin/clientes/eliminar/{{$c->id}}?token={{csrf_token()}}" data-confirm="Tens a certeza que quer eliminar este cliente?" class="delete btn btn-danger">Eliminar</a>
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
