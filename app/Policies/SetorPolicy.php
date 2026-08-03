<?php

namespace App\Policies;

use App\Enums\UserRole;
use App\Models\Setor;
use App\Models\User;

class SetorPolicy
{
    public function create(User $user): bool
    {
        return $user->role === UserRole::SUPER_ADMIN;
    }
}