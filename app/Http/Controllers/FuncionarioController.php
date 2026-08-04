<?php

namespace App\Http\Controllers;

use App\Actions\Funcionarios\FuncionarioCreation;
use App\Http\Requests\Funcionarios\FuncionarioRequest;
use App\Models\Cargo;
use App\Models\Funcionario;
use App\Models\Setor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class FuncionarioController extends Controller
{
    public function create(Setor $setor): View
    {
        Gate::authorize('create', Funcionario::class);

        return view('funcionarios.create', [
            'setor' => $setor,
            'cargos' => Cargo::query()->orderBy('hierarchy')->orderBy('name')->get(),
        ]);
    }

    public function store(FuncionarioRequest $request, Setor $setor, FuncionarioCreation $action): RedirectResponse {
        $action->execute(
            actor: $request->user(),
            setor: $setor,
            data: $request->validated(),
        );

        return redirect()->route('dashboard.super-admin')->with('success', 'Funcionario criado com sucesso.');
    }
}
