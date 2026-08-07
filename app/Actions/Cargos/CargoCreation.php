<?php

namespace App\Actions\Cargos;

use App\Models\Cargo;
use App\Models\User;
use Illuminate\Support\Facades\Gate;

class CargoCreation
{
    public function execute(User $actor, array $data): Cargo
    {
        Gate::forUser($actor)->authorize('create', Cargo::class);

        return Cargo::create([
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
            'hierarchy' => $data['hierarchy'],
        ]);
    }
}
