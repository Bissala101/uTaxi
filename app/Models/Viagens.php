<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Viagens
 *
 * @property int $id
 * @property string $posicao_x
 * @property string $posicao_y
 * @property string $motorista_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens query()
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens whereMotoristaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens wherePosicaoX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens wherePosicaoY($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens whereUpdatedAt($value)
 * @property string|null $preco
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens wherePreco($value)
 * @property int $cliente_id
 * @property int $estado
 * @property string|null $km
 * @property string|null $tempo_estimado
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens whereClienteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens whereKm($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Viagens whereTempoEstimado($value)
 * @mixin \Eloquent
 */
class Viagens extends Model
{
    protected $table = "viagens";
    protected $guarded = false;

    const ESTADO_ACTVIO = 0;
    const ESTADO_CONCLUIDO = 1;
    const ESTADO_EM_ESPERA = 2;


    function motorista(){
        return $this->belongsTo(Motoristas::class, "motorista_id", "id")->first();
    }

    function cliente(){
        return $this->belongsTo(User::class, "cliente_id", "id")->first();
    }
}
