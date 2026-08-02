<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    use HasFactory;

    protected $table = 'cargos';

    protected $fillable = [
        'name',
        'description',
        'hierarchy',
    ];

    protected function casts(): array
    {
        return [
            'hierarchy' => 'integer',
        ];
    }

    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class, 'cargo_id');
    }

    public function solicitacoesCargoAtual(): HasMany
    {
        return $this->hasMany(Solicitacao::class, 'cargo_atual_id');
    }

    public function solicitacoesCargoProposto(): HasMany
    {
        return $this->hasMany(Solicitacao::class, 'cargo_proposto_id');
    }
}
