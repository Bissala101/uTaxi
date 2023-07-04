@extends("admin.app", ["titulo"=>"Relatório de motoristas"])


@section("content")
    <div class="container mt-5">

        <?php
        $motoristas = \App\Models\Motoristas::orderBy("id", "desc")->get();
        ?>

        <div class="row justify-content-center">
            <div class="col-md-12 mb-3"><h2>Relatório de motoristas</h2></div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header p-3"><h5 class="m-0">Opções de seleção</h5></div>
                    @if ($errors->count() != 0)
                        <div class="p-3">
                            <div class="alert alert-danger">{{$errors->first()}}</div>
                        </div>
                    @endif
                    <div class="card-body">
                        <form method="get" action="/admin/relatorios/motoristas">

                            <div class="form-group mb-3">
                                <label>Motorista</label>
                                <select required name="cliente_id" class="form-select">
                                    @foreach($motoristas as $m)
                                        <option
                                            {{request()->query("cliente_id") == $m->id ? "selected":""}} value="{{$m->id}}">{{$m->nome}}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="form-group mb-3">
                                <label>Data</label>
                                <input required value="{{request()->query("data")}}" placeholder="Data" type="date"
                                       class="form-control" name="data">
                            </div>

                            <div class="form-group mb-3">
                                <button class="btn btn-primary">Verificar</button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
            <div class="col-md-8">
                @if(request()->query("cliente_id") && request()->query("data"))

                        <?php
                        $user = \App\Models\Motoristas::find(request()->query("cliente_id"));
                        $data = \Carbon\Carbon::make(request()->query("data"));

                    if ($user){

                        $viagens = \App\Models\Viagens::where("motorista_id", $user->id)->whereDate("created_at", $data)->where("estado", 1);

                        ?>

                    <div class="card">
                        <div class="card-header"><h5 class="m-0">Relatório</h5></div>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Data selecionada:</strong> {{$data->format("d/m/Y")}}
                            </li>
                            <li class="list-group-item"><strong>Motorista selecionado:</strong> {{$user->nome}}</li>
                            <li class="list-group-item"><strong>Viagens realizadas:</strong> {{$viagens->count()}}</li>
                            <li class="list-group-item"><strong>Total akz:</strong> {{\App\Helpers\Utils::formataDinheiro($viagens->sum("preco"))}}</li>
                        </ul>
                    </div>

                        <?php
                    }
                        ?>
                @endif
            </div>
        </div>
    </div>
@endsection
