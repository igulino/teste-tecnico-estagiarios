<?php

namespace App\Actions\Funcionarios;

use App\Models\Funcionario;
use App\Models\Historico;
use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class FuncionarioExclusion
{
    public function execute(User $actor, Funcionario $funcionario): void
    {
        Gate::forUser($actor)->authorize('delete', $funcionario);

        DB::transaction(function () use ($actor, $funcionario): void {
            $solicitacaoIds = Solicitacao::query()->where('funcionario_id', $funcionario->id)->pluck('id');

            if ($solicitacaoIds->isNotEmpty()) {
                Historico::query()->whereIn('solicitacao_id', $solicitacaoIds)->update(['solicitacao_id' => null]);

                Solicitacao::query()->whereIn('id', $solicitacaoIds)->delete();
            }

            Historico::create([
                'tipo' => 'exclusao_funcionario',
                'contexto' => 'Funcionario excluido por ' . $actor->name . '. Solicitacoes associadas tambem foram excluidas.',
                'funcionario_id' => $funcionario->id,
                'executado_por_user_id' => $actor->id,
                'setor_origem_id' => $funcionario->setor_id,
                'salario_anterior' => $funcionario->salary,
                'cargo_anterior_id' => $funcionario->cargo_id,
                'nome_funcionario_snapshot' => $funcionario->name,
            ]);

            $funcionario->delete();
        });
    }
}
