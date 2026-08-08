<?php

namespace App\Actions\Funcionarios;

use App\Models\Funcionario;
use App\Models\Historico;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class FuncionarioRestoration
{
    public function execute(User $actor, Funcionario $funcionario): void
    {
        Gate::forUser($actor)->authorize('restore', $funcionario);

        DB::transaction(function () use ($actor, $funcionario): void {
            $funcionario->restore();

            Historico::create([
                'tipo' => 'restauracao_funcionario',
                'contexto' => 'Exclusao de funcionario desfeita por ' . $actor->name . '.',
                'funcionario_id' => $funcionario->id,
                'executado_por_user_id' => $actor->id,
                'setor_destino_id' => $funcionario->setor_id,
                'salario_novo' => $funcionario->salary,
                'cargo_novo_id' => $funcionario->cargo_id,
                'nome_funcionario_snapshot' => $funcionario->name,
            ]);
        });
    }
}
