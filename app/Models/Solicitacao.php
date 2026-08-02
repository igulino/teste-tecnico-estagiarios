<?php

namespace App\Models;

use App\Enums\SolicitacaoStatus;
use App\Enums\SolicitacaoTipo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Solicitacao extends Model
{
    use HasFactory;

    protected $table = 'solicitacoes';

    protected $fillable = [
        'tipo',
        'status',
        'funcionario_id',
        'solicitado_por_user_id',
        'decidido_por_user_id',
        'setor_aprovador_id',
        'setor_origem_id',
        'setor_destino_id',
        'salario_atual',
        'salario_proposto',
        'cargo_atual_id',
        'cargo_proposto_id',
        'motivo_solicitacao',
        'justificativa_decisao',
        'decidido_em',
    ];

    protected function casts(): array
    {
        return [
            'tipo' => SolicitacaoTipo::class,
            'status' => SolicitacaoStatus::class,
            'salario_atual' => 'decimal:2',
            'salario_proposto' => 'decimal:2',
            'decidido_em' => 'datetime',
        ];
    }

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class, 'funcionario_id');
    }

    public function solicitadoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'solicitado_por_user_id');
    }

    public function decididoPor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'decidido_por_user_id');
    }

    public function setorAprovador(): BelongsTo
    {
        return $this->belongsTo(Setor::class, 'setor_aprovador_id');
    }

    public function setorOrigem(): BelongsTo
    {
        return $this->belongsTo(Setor::class, 'setor_origem_id');
    }

    public function setorDestino(): BelongsTo
    {
        return $this->belongsTo(Setor::class, 'setor_destino_id');
    }

    public function cargoAtual(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'cargo_atual_id');
    }

    public function cargoProposto(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'cargo_proposto_id');
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(Historico::class, 'solicitacao_id');
    }
}
