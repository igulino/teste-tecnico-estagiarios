<?php

namespace App\Queries;

use App\Enums\UserRole;
use App\Models\Funcionario;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ListarFuncionariosQuery
{
    public function execute(User $user): LengthAwarePaginator
    {
        $query = Funcionario::query()
            ->with(['setor', 'cargo']);

        if ($user->role !== UserRole::SUPER_ADMIN) {
            $query->where('setor_id', $user->setor_id);
        }

        return $query
            ->orderBy('nome')
            ->paginate(15);
    }
}