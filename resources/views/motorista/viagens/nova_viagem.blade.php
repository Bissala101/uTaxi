@extends("motorista.app", ["titulo"=>"Nova viagem"])
@section("content")
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <?php
                $clientes = \App\Models\User::orderBy("id", "desc")->get();
                ?>

                <form method="post" action="/motorista/viagens/processar">
                    @csrf

                    @if ($errors->count() != 0)
                        <div class="p-3">
                            <div class="alert alert-danger">{{$errors->first()}}</div>
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <label>Digita a posição y de destino</label>
                        <input required value="{{old("p_d_y")}}" placeholder="Posição y de destino" type="number"
                               class="form-control" name="p_d_y">
                    </div>

                    <div class="form-group mb-3">
                        <label>Digita a sua posição x de destino</label>
                        <input required value="{{old("p_d_x")}}" placeholder="Posição x de destino" type="number"
                               class="form-control" name="p_d_x">
                    </div>

                    <div class="form-group mb-3">
                        <label>Cliente</label>
                        <select required name="cliente_id" class="form-select">
                            @foreach($clientes as $m)
                                <option value="{{$m->id}}">{{$m->nome}}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group mb-3">
                        <button class="btn btn-primary">Continuar</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
@endsection
