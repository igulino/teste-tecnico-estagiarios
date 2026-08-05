<?php

namespace App\Http\Controllers;

use App\Actions\Funcionarios\FuncionarioCreation;
use App\Actions\Funcionarios\FuncionarioExclusion;
use App\Actions\Funcionarios\FuncionarioTransfer;
use App\Http\Requests\Funcionarios\FuncionarioRequest;
use App\Http\Requests\Funcionarios\FuncionarioTransferRequest;
use App\Models\Cargo;
use App\Models\Funcionario;
use App\Models\Setor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;


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

    public function destroy(Request $request, Funcionario $funcionario, FuncionarioExclusion $action): RedirectResponse
    {
        $action->execute(
            actor: $request->user(),
            funcionario: $funcionario
        );

        return redirect()->route('dashboard.super-admin')->with('success', 'Funcionario excluido com sucesso.');
    }

    public function transfer(FuncionarioTransferRequest $request, Funcionario $funcionario, FuncionarioTransfer $action): RedirectResponse
    {
        Log::info('funcionario: ' . $funcionario);
        $action->execute(
            actor: $request->user(),
            funcionario: $funcionario,
            data: $request->validated(),
        );

        return redirect()->route('dashboard.super-admin')->with('success', 'Funcionario transferido de setor com sucesso.');
    }

}
