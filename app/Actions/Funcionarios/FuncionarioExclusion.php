<?php

namespace App\Actions\Funcionarios;

use App\Models\Funcionario;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class FuncionarioExclusion
{
    public function execute(User $actor, Funcionario $funcionario): void
    {
        Gate::forUser($actor)->authorize('delete', $funcionario);

        $funcionario->delete();
    }
}
