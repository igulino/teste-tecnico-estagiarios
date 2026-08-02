<?php

namespace App\Actions\Setores;

use App\Enums\UserRole;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;

class SetorCreation
{
    public function execute(User $actor, array $data): Setor {
        Gate::forUser($actor)->authorize('create', Setor::class);

        return DB::transaction(function () use ($data): Setor {
            $setor = Setor::create([
                'nome' => $data['nome'],
                'descricao' => $data['descricao'] ?? null,
            ]);

            User::create([
                'name' => $data['admin_nome'],
                'email' => $data['admin_email'],
                'password' => Hash::make($data['admin_password']),
                'role' => UserRole::ADMIN_SETOR,
                'setor_id' => $setor->id,
            ]);

            return $setor;
        });
    }
}