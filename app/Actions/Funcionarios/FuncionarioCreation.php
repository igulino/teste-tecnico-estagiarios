<?php

namespace App\Actions\Funcionarios;

use App\Models\Funcionario;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class FuncionarioCreation
{
    public function execute(User $actor, Setor $setor, array $data): Funcionario
    {
        Gate::forUser($actor)->authorize('create', Funcionario::class);

        return Funcionario::create([
            'name' => $data['name'],
            'salary' => $data['salary'],
            'cargo_id' => $data['cargo_id'],
            'setor_id' => $setor->id,
        ]);
    }
}
