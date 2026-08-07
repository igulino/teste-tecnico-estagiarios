<?php

namespace App\Actions\Solicitacoes;

use App\Enums\SolicitacaoStatus;
use App\Enums\SolicitacaoTipo;
use App\Models\Funcionario;
use App\Models\Historico;
use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use RuntimeException;

class SolicitacaoDecision
{
    public function solicitarTransferencia(User $actor, array $data): Solicitacao
    {
        Gate::forUser($actor)->authorize('create', Solicitacao::class);

        return DB::transaction(function () use ($actor, $data): Solicitacao {
            $funcionario = Funcionario::query()->findOrFail($data['funcionario_id']);

            return Solicitacao::create([
                'tipo' => SolicitacaoTipo::TRANSFERENCIA->value,
                'status' => SolicitacaoStatus::PENDENTE->value,
                'funcionario_id' => $funcionario->id,
                'solicitado_por_user_id' => $actor->id,
                'setor_aprovador_id' => $data['setor_destino_id'],
                'setor_origem_id' => $funcionario->setor_id,
                'setor_destino_id' => $data['setor_destino_id'],
            ]);
        });
    }

    public function solicitarAumento(User $actor, array $data): Solicitacao
    {
        Gate::forUser($actor)->authorize('create', Solicitacao::class);

        return DB::transaction(function () use ($actor, $data): Solicitacao {
            $funcionario = Funcionario::query()->findOrFail($data['funcionario_id']);

            return Solicitacao::create([
                'tipo' => SolicitacaoTipo::AUMENTO->value,
                'status' => SolicitacaoStatus::PENDENTE->value,
                'funcionario_id' => $funcionario->id,
                'solicitado_por_user_id' => $actor->id,
                'setor_aprovador_id' => $funcionario->setor_id,
                'setor_origem_id' => $funcionario->setor_id,
                'salario_atual' => $funcionario->salary,
                'salario_proposto' => $data['salario_proposto'],
            ]);
        });
    }

    public function execute(User $actor, Solicitacao $solicitacao, string $justificativa): void
    {
        Gate::forUser($actor)->authorize('decide', $solicitacao);

        DB::transaction(function () use ($actor, $solicitacao, $justificativa) {
            $solicitacao = Solicitacao::query()->lockForUpdate()->findOrFail($solicitacao->id);
            
            if ($solicitacao->status !== SolicitacaoStatus::PENDENTE) {
                throw new RuntimeException('Solicitacao ja decidida.');
            }

            match ($solicitacao->tipo) {
                SolicitacaoTipo::TRANSFERENCIA =>
                    $this->aplicarTransferencia(
                        $solicitacao,
                        $actor,
                    ),

                SolicitacaoTipo::AUMENTO =>
                    $this->aplicarAumento(
                        $solicitacao,
                        $actor,
                    ),

                SolicitacaoTipo::PROMOCAO =>
                    $this->aplicarPromocao(
                        $solicitacao,
                        $actor,
                    ),
            };

            $solicitacao->update([
                'status' => SolicitacaoStatus::APROVADA,
                'decidido_por_user_id' => $actor->id,
                'justificativa_decisao' => $justificativa,
                'decidido_em' => now(),
            ]);
        });
    }

    public function reject(User $actor, Solicitacao $solicitacao, string $justificativa): void
    {
        Gate::forUser($actor)->authorize('decide', $solicitacao);

        DB::transaction(function () use ($actor, $solicitacao, $justificativa) {
            $solicitacao = Solicitacao::query()->lockForUpdate()->findOrFail($solicitacao->id);

            if ($solicitacao->status !== SolicitacaoStatus::PENDENTE) {
                throw new RuntimeException('Solicitacao ja decidida.');
            }

            $solicitacao->update([
                'status' => SolicitacaoStatus::REPROVADA,
                'decidido_por_user_id' => $actor->id,
                'justificativa_decisao' => $justificativa,
                'decidido_em' => now(),
            ]);
        });
    }

    private function aplicarTransferencia(Solicitacao $solicitacao, User $actor): void
    {
        Gate::forUser($actor)->authorize('decide', $solicitacao);

        $funcionario = Funcionario::query()->lockForUpdate()->findOrFail($solicitacao->funcionario_id);
        $setorOrigemId = $funcionario->setor_id;
        $funcionario->update([
            'setor_id' => $solicitacao->setor_destino_id,
        ]);

        Historico::create([
            'tipo' => SolicitacaoTipo::TRANSFERENCIA->value,
            'contexto' => 'Transferencia aprovada por ' . $actor->name . ' pela solicitacao de ' . User::query()->where('id', $solicitacao->solicitado_por_user_id)->value('name'),
            'funcionario_id' => $funcionario->id,
            'solicitacao_id' => $solicitacao->id,
            'executado_por_user_id' => $actor->id,
            'setor_origem_id' => $setorOrigemId,
            'setor_destino_id' => $solicitacao->setor_destino_id,
            'nome_funcionario_snapshot' => $funcionario->name,
        ]);
    }

    private function aplicarAumento(Solicitacao $solicitacao, User $actor): void
    {
        $funcionario = Funcionario::query()->lockForUpdate()->findOrFail($solicitacao->funcionario_id);
        $salarioAnterior = $funcionario->salary;

        $funcionario->update([
            'salary' => $solicitacao->salario_proposto,
        ]);

        Historico::create([
            'tipo' => SolicitacaoTipo::AUMENTO->value,
            'contexto' => 'Aumento salarial aprovado por solicitacao.',
            'funcionario_id' => $funcionario->id,
            'solicitacao_id' => $solicitacao->id,
            'executado_por_user_id' => $actor->id,
            'salario_anterior' => $salarioAnterior,
            'salario_novo' => $solicitacao->salario_proposto,
            'nome_funcionario_snapshot' => $funcionario->name,
        ]);
    }

    private function aplicarPromocao(Solicitacao $solicitacao, User $actor): void
    {
        $funcionario = Funcionario::query()->lockForUpdate()->findOrFail($solicitacao->funcionario_id);
        $cargoAnteriorId = $funcionario->cargo_id;

        $funcionario->update([
            'cargo_id' => $solicitacao->cargo_proposto_id,
        ]);

        Historico::create([
            'tipo' => SolicitacaoTipo::PROMOCAO->value,
            'contexto' => 'Promocao aprovada por solicitacao.',
            'funcionario_id' => $funcionario->id,
            'solicitacao_id' => $solicitacao->id,
            'executado_por_user_id' => $actor->id,
            'cargo_anterior_id' => $cargoAnteriorId,
            'cargo_novo_id' => $solicitacao->cargo_proposto_id,
            'nome_funcionario_snapshot' => $funcionario->name,
        ]);
    }
}
