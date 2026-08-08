<?php

namespace App\Policies;

use App\Enums\SolicitacaoStatus;
use App\Enums\UserRole;
use App\Models\Solicitacao;
use App\Models\User;

class SolicitacaoPolicy
{
    public function create(User $user): bool
    {
        return $user->role === UserRole::ADMIN_SETOR
            && $user->setor_id !== null;
    }

    public function view(User $user, Solicitacao $solicitacao): bool
    {
        return $user->role === UserRole::SUPER_ADMIN
            || $user->setor_id === $solicitacao->setor_aprovador_id;
    }

    public function decide(User $user, Solicitacao $solicitacao): bool
    {
        return $user->role === UserRole::SUPER_ADMIN
            || (
                $user->role === UserRole::ADMIN_SETOR
                && $user->setor_id === $solicitacao->setor_aprovador_id
            );
    }

    public function delete(User $user, Solicitacao $solicitacao): bool
    {
        return $user->role === UserRole::ADMIN_SETOR
            && $user->id === $solicitacao->solicitado_por_user_id
            && $solicitacao->status === SolicitacaoStatus::PENDENTE;
    }
}
