@extends("motorista.app", ["titulo"=>"uTaxi Motorista"])
@section("content")

                <?php
                    $viagens = \App\Models\Viagens::where("motorista_id", auth()->id())->limit(4)->get();
                ?>

                <h2>Viagens recentes</h2>
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

                    @if($viagens->count() == 0)
                        <div class="p-5"><h5 class="text-center text-danger">Nenhuma viagem</h5></div>
                    @endif

                    @if($viagens->count() != 0)
                        <a href="/motorista/viagens/realizadas" class="btn btn-primary">Ver todas</a>
                    @endif
@endsection
