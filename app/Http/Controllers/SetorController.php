<?php

namespace App\Http\Controllers;

use App\Actions\Setores\SetorCreation;
use App\Http\Requests\Setores\SetoresRequest;
use App\Models\Setor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SetorController extends Controller
{
    public function create(): View
    {
        Gate::authorize('create', Setor::class);

        return view('setores.create');
    }

    public function store(SetoresRequest $request, SetorCreation $action): RedirectResponse
    {
        $action->execute(
            actor: $request->user(),
            data: $request->validated(),
        );

        return redirect()
            ->route('dashboard.super-admin')
            ->with('success', 'Setor criado com sucesso.');
    }
}
