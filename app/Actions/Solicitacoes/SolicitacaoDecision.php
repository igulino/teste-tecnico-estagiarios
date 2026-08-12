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
use Illuminate\Validation\ValidationException;
use RuntimeException;

class SolicitacaoDecision
{
    public function solicitarTransferencia(User $actor, array $data): Solicitacao
    {
        Gate::forUser($actor)->authorize('create', Solicitacao::class);

        return DB::transaction(function () use ($actor, $data): Solicitacao {
            $funcionario = Funcionario::query()->lockForUpdate()->findOrFail($data['funcionario_id']);

            $this->garantirQueNaoExisteSolicitacaoPendente(
                $funcionario,
                SolicitacaoTipo::TRANSFERENCIA,
                'Este funcionario ja possui uma transferencia pendente.',
            );

            Historico::create([
                'tipo' => 'solicitação_Transferência',
                'contexto' => 'Transferência de ' . $funcionario->name . ' solicitada por ' . $actor->name,
                'funcionario_id' => $funcionario->id,
                'executado_por_user_id' => $actor->id,
                'setor_origem_id' => $funcionario->setor_id,
                'salario_anterior' => $funcionario->salary,
                'cargo_anterior_id' => $funcionario->cargo_id,
                'nome_funcionario_snapshot' => $funcionario->name,
            ]);
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
            $funcionario = Funcionario::query()->lockForUpdate()->findOrFail($data['funcionario_id']);

            $this->garantirQueNaoExisteSolicitacaoPendente(
                $funcionario,
                SolicitacaoTipo::AUMENTO,
                'Este funcionario ja possui um aumento salarial pendente.',
            );
             Historico::create([
                'tipo' => 'solicitação_Aumento',
                'contexto' => 'Aumento de ' . $funcionario->name . ' solicitado por ' . $actor->name,
                'funcionario_id' => $funcionario->id,
                'executado_por_user_id' => $actor->id,
                'setor_origem_id' => $funcionario->setor_id,
                'salario_anterior' => $funcionario->salary,
                'cargo_anterior_id' => $funcionario->cargo_id,
                'nome_funcionario_snapshot' => $funcionario->name,
            ]);

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

    public function solicitarPromocao(User $actor, array $data): Solicitacao
    {
        Gate::forUser($actor)->authorize('create', Solicitacao::class);

        return DB::transaction(function () use ($actor, $data): Solicitacao {
            $funcionario = Funcionario::query()->lockForUpdate()->findOrFail($data['funcionario_id']);

            $this->garantirQueNaoExisteSolicitacaoPendente(
                $funcionario,
                SolicitacaoTipo::PROMOCAO,
                'Este funcionario ja possui uma mudanca de cargo pendente.',
            );

             Historico::create([
                'tipo' => 'solicitação_Promoção',
                'contexto' => 'Promoção de ' . $funcionario->name . ' solicitada por ' . $actor->name,
                'funcionario_id' => $funcionario->id,
                'executado_por_user_id' => $actor->id,
                'setor_origem_id' => $funcionario->setor_id,
                'salario_anterior' => $funcionario->salary,
                'cargo_anterior_id' => $funcionario->cargo_id,
                'nome_funcionario_snapshot' => $funcionario->name,
            ]);

            return Solicitacao::create([
                'tipo' => SolicitacaoTipo::PROMOCAO->value,
                'status' => SolicitacaoStatus::PENDENTE->value,
                'funcionario_id' => $funcionario->id,
                'solicitado_por_user_id' => $actor->id,
                'setor_aprovador_id' => $funcionario->setor_id,
                'setor_origem_id' => $funcionario->setor_id,
                'cargo_atual_id' => $funcionario->cargo_id,
                'cargo_proposto_id' => $data['cargo_proposto_id'],
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
            $funcionario = Funcionario::query()->findOrFail($solicitacao->funcionario_id);

            $solicitacao->update([
                'status' => SolicitacaoStatus::REPROVADA,   
                'decidido_por_user_id' => $actor->id,
                'justificativa_decisao' => $justificativa,
                'decidido_em' => now(),
            ]);

            Historico::create([
                'tipo' => $solicitacao->tipo->value,
                'contexto' => 'Solicitacao recusada por ' . $actor->name . '.',
                'funcionario_id' => $funcionario->id,
                'solicitacao_id' => $solicitacao->id,
                'executado_por_user_id' => $actor->id,
                'setor_origem_id' => $solicitacao->setor_origem_id,
                'setor_destino_id' => $solicitacao->setor_destino_id,
                'salario_anterior' => $solicitacao->salario_atual,
                'salario_novo' => $solicitacao->salario_proposto,
                'cargo_anterior_id' => $solicitacao->cargo_atual_id,
                'cargo_novo_id' => $solicitacao->cargo_proposto_id,
                'nome_funcionario_snapshot' => $funcionario->name,
            ]);
        });
    }

    public function undo(User $actor, Solicitacao $solicitacao): void
    {
        Gate::forUser($actor)->authorize('delete', $solicitacao);

        DB::transaction(function () use ($actor, $solicitacao) {
            $solicitacao = Solicitacao::query()->lockForUpdate()->findOrFail($solicitacao->id);

            if ($solicitacao->status !== SolicitacaoStatus::PENDENTE) {
                throw new RuntimeException('Apenas solicitacoes pendentes podem ser desfeitas.');
            }

            $funcionario = Funcionario::query()->findOrFail($solicitacao->funcionario_id);

            Historico::create([
                'tipo' => $solicitacao->tipo->value,
                'contexto' => 'Solicitacao desfeita por ' . $actor->name . '.',
                'funcionario_id' => $funcionario->id,
                'solicitacao_id' => null,
                'executado_por_user_id' => $actor->id,
                'setor_origem_id' => $solicitacao->setor_origem_id,
                'setor_destino_id' => $solicitacao->setor_destino_id,
                'salario_anterior' => $solicitacao->salario_atual,
                'salario_novo' => $solicitacao->salario_proposto,
                'cargo_anterior_id' => $solicitacao->cargo_atual_id,
                'cargo_novo_id' => $solicitacao->cargo_proposto_id,
                'nome_funcionario_snapshot' => $funcionario->name,
            ]);

            $solicitacao->delete();
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

    private function garantirQueNaoExisteSolicitacaoPendente(Funcionario $funcionario, SolicitacaoTipo $tipo, string $mensagem): void
    {
        $existeSolicitacaoPendente = Solicitacao::query()
            ->where('tipo', $tipo->value)
            ->where('status', SolicitacaoStatus::PENDENTE->value)
            ->where('funcionario_id', $funcionario->id)->exists();

        if ($existeSolicitacaoPendente) {
            throw ValidationException::withMessages([
                'funcionario_id' => $mensagem,
            ]);
        }
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
            'contexto' => 'Aumento salarial aprovado por ' . $actor->name . '.',
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
