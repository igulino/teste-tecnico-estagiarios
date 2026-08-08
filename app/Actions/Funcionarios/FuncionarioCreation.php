<?php

namespace App\Actions\Funcionarios;

use App\Enums\UserRole;
use App\Models\Funcionario;
use App\Models\Historico;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class FuncionarioCreation
{
    public function execute(User $actor, Setor $setor, array $data): Funcionario
    {
        Gate::forUser($actor)->authorize('create', Funcionario::class);

        abort_if(
            $actor->role === UserRole::ADMIN_SETOR && $actor->setor_id !== $setor->id,
            403
        );

        return DB::transaction(function () use ($actor, $setor, $data): Funcionario {
            $funcionario = Funcionario::create([
                'name' => $data['name'],
                'salary' => $data['salary'],
                'cargo_id' => $data['cargo_id'],
                'setor_id' => $setor->id,
            ]);

            Historico::create([
                'tipo' => 'criacao_funcionario',
                'contexto' => 'Funcionario criado por ' . $actor->name . '.',
                'funcionario_id' => $funcionario->id,
                'executado_por_user_id' => $actor->id,
                'setor_destino_id' => $setor->id,
                'salario_novo' => $funcionario->salary,
                'cargo_novo_id' => $funcionario->cargo_id,
                'nome_funcionario_snapshot' => $funcionario->name,
            ]);

            return $funcionario;
        });
    }
}
