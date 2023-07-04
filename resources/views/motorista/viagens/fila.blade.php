@extends("motorista.app", ["titulo"=>"Lista de espera"])
@section("content")

                <?php
                    $viagens = \App\Models\Viagens::where("motorista_id", auth()->guard("motorista")->id())->where("estado", \App\Models\Viagens::ESTADO_EM_ESPERA)->get();
                ?>

                <h2 class="mb-5">Lista de espera</h2>
                    @foreach($viagens as $v)

                        <?php
                            $motorista = $v->motorista();
                            $cliente = $v->cliente();
                            $carro = $motorista->carro();
                        ?>

                        <div class="card ">
                            <div class="card-body">
                                <h5>Codigo da viagem <strong>#{{$v->id}}</strong></h5><br />


                                <strong>Cliente:</strong> {{$cliente->nome}}<br />
                                <strong>Email:</strong> {{$cliente->email}}<br />

                                <!--
                                <hr />
                                <strong>Modelo do veículo:</strong> {{$carro->modelo}}<br />
                                <strong>Tipo de veículo:</strong> {{$carro->tipo}}<br />
                                <strong>Velocidade média do veículo:</strong> {{$carro->velocidade_media}}<br />
                                -->

                                <hr />
                                <strong>Total a pagar:</strong> {{\App\Helpers\Utils::formataDinheiro($v->preco)}}<br />
                                <strong>Estado:</strong> <span class="text-danger">Em espera</span><br /><br />
                                <a class="btn btn-primary" href="/motorista/viagens/atender/{{$v->id}}">Atender agora</a>
                            </div>
                        </div>
                        <br />
                    @endforeach
@endsection
