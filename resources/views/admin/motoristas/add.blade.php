@extends("admin.app", ["titulo"=>"Adicionar motorista"])


@section("content")
    <div class="container mt-5">

        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card">
                    <div class="card-header p-3"><h2 class="m-0">Adicionar motorista</h2></div>
                    @if ($errors->count() != 0)
                        <div class="p-3">
                            <div class="alert alert-danger">{{$errors->first()}}</div>
                        </div>
                    @endif
                    <div class="card-body">
                        <form method="post" action="/admin/motoristas/add">
                            @csrf

                            <div class="form-group mb-3">
                                <label>Nome</label>
                                <input value="{{old("nome")}}" type="text" placeholder="Nome" class="form-control" name="nome">
                            </div>

                            <div class="form-group mb-3">
                                <label>Email</label>
                                <input value="{{old("email")}}" placeholder="Email" type="email" class="form-control" name="email">
                            </div>

                            <div class="form-group mb-3">
                                <label>Palavra passe</label>
                                <input value="{{old("password")}}" placeholder="Palavra passe" type="text" class="form-control" name="password">
                            </div>

                            <div class="form-group mb-3">
                                <label>Morada</label>
                                <input value="{{old("morada")}}" placeholder="Morada" type="text" class="form-control" name="morada">
                            </div>

                            <div class="form-group mb-3">
                                <label>Data de nascimento</label>
                                <input value="{{old("data_nascimento")}}" placeholder="Data de nascimento" type="date" class="form-control" name="data_nascimento">
                            </div>

                            <div class="form-group mb-3">
                               <button class="btn btn-primary">Adicionar</button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection
