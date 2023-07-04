@extends("motorista.app", ["titulo"=>"Viagens realizadas"])
@section("content")

                <?php
                    $viagens = \App\Models\Viagens::where("motorista_id", auth()->guard("motorista")->id())->where("estado", \App\Models\Viagens::ESTADO_CONCLUIDO)->get();
                ?>

                <h2 class="mb-5">Viagens realizadas</h2>
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
                                <strong>Total pago:</strong> {{\App\Helpers\Utils::formataDinheiro($v->preco)}}<br />
                                <strong>Estado:</strong> <span class="text-success">Concluído</span><br />
                            </div>
                        </div>
                        <br />
                    @endforeach
@endsection
