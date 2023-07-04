<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Carros
 *
 * @property int $id
 * @property string $tipo
 * @property int $velocidade_media
 * @property int $posicao_y
 * @property int $posicao_x
 * @property int $motorista_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Carros newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carros newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Carros query()
 * @method static \Illuminate\Database\Eloquent\Builder|Carros whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carros whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carros whereMotoristaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carros wherePosicaoX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carros wherePosicaoY($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carros whereTipo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carros whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carros whereVelocidadeMedia($value)
 * @property string $nome
 * @property string $modelo
 * @property string $preco
 * @method static \Illuminate\Database\Eloquent\Builder|Carros whereModelo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carros whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Carros wherePreco($value)
 * @property int $fiabilidade
 * @method static \Illuminate\Database\Eloquent\Builder|Carros whereFiabilidade($value)
 * @mixin \Eloquent
 */
class Carros extends Model
{
    protected $table = "carros";
    protected $guarded = false;

    function motorista(){
        return $this->belongsTo(Motoristas::class, "motorista_id", "id")->first();
    }
}
