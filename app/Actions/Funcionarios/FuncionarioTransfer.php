<?php

namespace App\Actions\Funcionarios;

use App\Models\Funcionario;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class FuncionarioTransfer
{
    public function execute(User $actor, Funcionario $funcionario, array $data): Funcionario
    {
        Gate::forUser($actor)->authorize('update', $funcionario);

        $funcionario->update([
            'setor_id' => $data['setor_id'],
        ]);

        return $funcionario;
    }
}
