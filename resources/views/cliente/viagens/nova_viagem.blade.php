@extends("cliente.app", ["titulo"=>"Nova viagem"])
@section("content")
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <?php
                    $motoristas = \App\Models\Motoristas::orderBy("id", "desc")->whereExists(function($query){
                        $query->from("carros")->whereRaw("carros.motorista_id = motorista.id");
                    })->get();
                ?>

                <form method="post" action="/viagens/processar">
                    @csrf

                    @if ($errors->count() != 0)
                        <div class="p-3">
                            <div class="alert alert-danger">{{$errors->first()}}</div>
                        </div>
                    @endif

                    <div class="form-group mb-3">
                        <label>Digita a sua posição y</label>
                        <input required value="{{old("p_y")}}" placeholder="Posição y" type="number" class="form-control" name="p_y">
                    </div>

                    <div class="form-group mb-3">
                        <label>Digita a sua posição x</label>
                        <input required value="{{old("p_x")}}" placeholder="Posição x" type="number" class="form-control" name="p_x">
                    </div>

                    <div class="form-group mb-3">
                        <label>Digita a posição y de destino</label>
                        <input required value="{{old("p_d_y")}}" placeholder="Posição y de destino" type="number" class="form-control" name="p_d_y">
                    </div>

                    <div class="form-group mb-3">
                        <label>Digita a sua posição x de destino</label>
                        <input required value="{{old("p_d_x")}}" placeholder="Posição x de destino" type="number" class="form-control" name="p_d_x">
                    </div>

                    <div class="form-group mb-3">
                        <label>Motorista</label>
                        <select required  name="motorista_id" class="form-select">
                            <option  value="0">Encontrar o motorista mais proximo</option>
                            @foreach($motoristas as $m)
                                <?php
                                    $carro = $m->carro();
                                ?>
                                <option  value="{{$m->id}}">{{$m->nome}} - {{$carro->modelo}}</option>
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
