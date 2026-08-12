<?php

namespace App\Actions\Setores;

use App\Enums\UserRole;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SetorCreation
{
    public function execute(User $actor, array $data): Setor {
        Gate::forUser($actor)->authorize('create', Setor::class);

        return DB::transaction(function () use ($data): Setor {
            $setor = Setor::create([
                'name' => $data['name'],
                'description' => $data['description'] ?? null,
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
        //a transaction vai impedir que tu faça uma interação e n faça a outra, resolve o problema do adm + setor brabo
    }
}
