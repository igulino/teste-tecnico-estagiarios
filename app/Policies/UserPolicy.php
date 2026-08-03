<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\User;

class UserPolicy
{
    public function viewSuperAdminDashboard(User $user): bool
    {
        return $user->role === UserRole::SUPER_ADMIN;
    }

    public function viewAdminSetorDashboard(User $user): bool
    {
        return $user->role === UserRole::ADMIN_SETOR
            && $user->setor_id !== null;
    }
}
