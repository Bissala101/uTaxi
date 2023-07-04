@extends("admin.app", ["titulo"=>"Adicionar cliente"])


@section("content")
    <div class="container mt-5">

        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card">
                    <div class="card-header p-3"><h2 class="m-0">Adicionar veículo</h2></div>
                    @if ($errors->count() != 0)
                        <div class="p-3">
                            <div class="alert alert-danger">{{$errors->first()}}</div>
                        </div>
                    @endif
                    <div class="card-body">
                        <form method="post" action="/admin/carros/add">
                            @csrf

                            <div class="form-group mb-3">
                                <label>Nome</label>
                                <input required value="{{old("nome")}}" placeholder="Nome" type="text" class="form-control" name="nome">
                            </div>

                            <div class="form-group mb-3">
                                <label>Modelo</label>
                                <input required value="{{old("modelo")}}" placeholder="Modelo" type="text" class="form-control" name="modelo">
                            </div>

                            <div class="form-group mb-3">
                                <label>Preço base por km</label>
                                <input required value="{{old("preco")}}" placeholder="Preço base por km" type="text" class="form-control" name="preco">
                            </div>

                            <div class="form-group mb-3">
                                <label>Tipo de veículo</label>
                                <select required  name="tipo" class="form-select">
                                    <option  value="Carros ligeiros">Carro ligeiro</option>
                                    <option value="Carrinha de novo lugar">Carrinha de novo lugar</option>
                                    <option value="Moto">Moto</option>
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Velocidade média</label>
                                <input required value="{{old("velocidade_media")}}" placeholder="Velocidade média" type="number" class="form-control" name="velocidade_media">
                            </div>

                            <div class="form-group mb-3">
                                <label>Fiabilidade</label>
                                <input required value="{{old("fiabilidade")}}" placeholder="Fiabilidade" type="number" class="form-control" name="fiabilidade">
                            </div>

                            <div class="form-group mb-3">
                                <label>Posição y</label>
                                <input required value="{{old("p_y")}}" placeholder="Posição y" type="number" class="form-control" name="p_y">
                            </div>

                            <div class="form-group mb-3">
                                <label>Posição x</label>
                                <input required value="{{old("p_x")}}" placeholder="Posição x" type="number" class="form-control" name="p_x">
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
