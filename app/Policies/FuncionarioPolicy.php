<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Funcionario;
use App\Models\User;

class FuncionarioPolicy
{
    public function create(User $user): bool
    {
        return $user->role === UserRole::SUPER_ADMIN
            || (
                $user->role === UserRole::ADMIN_SETOR
                && $user->setor_id !== null
            );
    }

    public function delete(User $user, Funcionario $funcionario): bool
    {
        return $user->role === UserRole::SUPER_ADMIN
            || (
                $user->role === UserRole::ADMIN_SETOR
                && $user->setor_id === $funcionario->setor_id
            );
    }

    public function restore(User $user, Funcionario $funcionario): bool
    {
        return $user->role === UserRole::ADMIN_SETOR
            && $user->setor_id === $funcionario->setor_id;
    }

    public function update(User $user): bool
    {
        return $user->role === UserRole::SUPER_ADMIN;
    }

}
