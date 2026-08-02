<?php

namespace App\Http\Controllers;

use App\Actions\Setores\SetorCreation;
use App\Http\Requests\SetoresRequest;
use Illuminate\Http\RedirectResponse;

class SetorController extends Controller
{
    public function store(SetoresRequest $request, SetorCreation $action): RedirectResponse {
        $action->execute(
            actor: $request->user(),
            data: $request->validated(),
        );

        return redirect()
            ->route('setores.index')
            ->with('success', 'Setor criado com sucesso.');
    }
}