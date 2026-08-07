<?php

namespace App\Http\Controllers;

use App\Actions\Cargos\CargoCreation;
use App\Http\Requests\Cargos\CargoRequest;
use App\Models\Cargo;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class CargoController extends Controller
{
    public function create(): View
    {
        Gate::authorize('create', Cargo::class);

        return view('cargos.create');
    }

    public function store(CargoRequest $request, CargoCreation $action): RedirectResponse
    {
        $action->execute(
            actor: $request->user(),
            data: $request->validated(),
        );

        return redirect()
            ->route('dashboard.super-admin')
            ->with('success', 'Cargo criado com sucesso.');
    }
}
