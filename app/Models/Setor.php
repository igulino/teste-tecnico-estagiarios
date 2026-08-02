<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Setor extends Model
{
    use HasFactory;

    protected $table = 'setores';

    protected $fillable = [
        'name',
        'description',
    ];

    public function admin(): HasOne
    {
        return $this->hasOne(User::class, 'setor_id');
    }

    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class, 'setor_id');
    }

    public function solicitacoesParaAprovar(): HasMany
    {
        return $this->hasMany(Solicitacao::class, 'setor_aprovador_id');
    }

    public function solicitacoesOrigem(): HasMany
    {
        return $this->hasMany(Solicitacao::class, 'setor_origem_id');
    }

    public function solicitacoesDestino(): HasMany
    {
        return $this->hasMany(Solicitacao::class, 'setor_destino_id');
    }
}
