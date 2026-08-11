<?php

namespace App\Actions\Funcionarios;

use App\Models\Funcionario;
use App\Models\Historico;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class FuncionarioTransfer
{
    public function execute(User $actor, Funcionario $funcionario, array $data): Funcionario
    {
        Gate::forUser($actor)->authorize('update', $funcionario);

        return DB::transaction(function () use ($actor, $funcionario, $data): Funcionario {
            $funcionario = Funcionario::query()->lockForUpdate()->findOrFail($funcionario->id);
            $setorOrigemId = $funcionario->setor_id;
            $setorDestinoId = (int) $data['setor_id'];

            if ($setorOrigemId === $setorDestinoId) {
                return $funcionario;
            }

            $funcionario->update([
                'setor_id' => $setorDestinoId,
            ]);

            Historico::create([
                'tipo' => 'transferencia_funcionario',
                'contexto' => 'Funcionario transferido diretamente por super admin.',
                'funcionario_id' => $funcionario->id,
                'executado_por_user_id' => $actor->id,
                'setor_origem_id' => $setorOrigemId,
                'setor_destino_id' => $setorDestinoId,
                'nome_funcionario_snapshot' => $funcionario->name,
            ]);

            return $funcionario;
        });
    }
}
