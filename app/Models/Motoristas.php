<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Motoristas
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas query()
 * @property int $id
 * @property string $nome
 * @property string $email
 * @property string $password
 * @property string $morada
 * @property string $data_nascimento
 * @property int $posicao_x
 * @property int $posicao_y
 * @property string|null $remember_token
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas whereDataNascimento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas whereMorada($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas whereNome($value)
 * @method static \Illuminate\Database\Eloquent\Busilder|Motoristas wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas wherePosicaoX($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas wherePosicaoY($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas whereUpdatedAt($value)
 * @property int $classificacao
 * @property string $km_realizado
 * @property string|null $cumprimento
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas whereClassificacao($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas whereCumprimento($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Motoristas whereKmRealizado($value)
 * @mixin \Eloquent
 */
class Motoristas extends Authenticatable
{
    protected $table = "motorista";
    protected $guarded = false;

    function carro(){
        return $this->hasMany(Carros::class, "motorista_id", "id")->first();
    }
}
