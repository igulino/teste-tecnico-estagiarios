<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Historico extends Model
{
    use HasFactory;

    protected $table = 'historicos';

    protected $fillable = [
        'tipo',
        'contexto',
        'funcionario_id',
        'solicitacao_id',
        'executado_por_user_id',
        'setor_origem_id',
        'setor_destino_id',
        'salario_anterior',
        'salario_novo',
        'cargo_anterior_id',
        'cargo_novo_id',
        'nome_funcionario_snapshot',
    ];

    protected function casts(): array
    {
        return [
            'salario_anterior' => 'decimal:2',
            'salario_novo' => 'decimal:2',
        ];
    }

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }

    public function solicitacao(): BelongsTo
    {
        return $this->belongsTo(Solicitacao::class, 'solicitacao_id');
    }

    public function executadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'executado_por_user_id');
    }

    public function setorOrigem(): BelongsTo
    {
        return $this->belongsTo(Setor::class, 'setor_origem_id');
    }

    public function setorDestino(): BelongsTo
    {
        return $this->belongsTo(Setor::class, 'setor_destino_id');
    }

    public function cargoAnterior(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'cargo_anterior_id');
    }

    public function cargoNovo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'cargo_novo_id');
    }
}
