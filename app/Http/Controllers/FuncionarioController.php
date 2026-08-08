<?php

namespace App\Http\Controllers;

use App\Actions\Funcionarios\FuncionarioCreation;
use App\Actions\Funcionarios\FuncionarioExclusion;
use App\Actions\Funcionarios\FuncionarioRestoration;
use App\Actions\Funcionarios\FuncionarioTransfer;
use App\Enums\UserRole;
use App\Http\Requests\Funcionarios\FuncionarioRequest;
use App\Http\Requests\Funcionarios\FuncionarioTransferRequest;
use App\Models\Cargo;
use App\Models\Funcionario;
use App\Models\Setor;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;


class FuncionarioController extends Controller
{
    public function excluded(Request $request): View
    {
        Gate::authorize('viewAdminSetorDashboard', User::class);

        return view('funcionarios.excluded', [
            'funcionarios' => Funcionario::onlyTrashed()
                ->with('cargo')
                ->where('setor_id', $request->user()->setor_id)
                ->orderByDesc('deleted_at')
                ->get(),
        ]);
    }

    public function create(Setor $setor): View
    {
        Gate::authorize('create', Funcionario::class);

        abort_if(
            request()->user()->role === UserRole::ADMIN_SETOR && request()->user()->setor_id !== $setor->id,
            403
        );

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

        return redirect()->route('dashboard')->with('success', 'Funcionario criado com sucesso.');
    }

    public function destroy(Request $request, Funcionario $funcionario, FuncionarioExclusion $action): RedirectResponse
    {
        $action->execute(
            actor: $request->user(),
            funcionario: $funcionario
        );

        return redirect()->back()->with('success', 'Funcionario excluido com sucesso.');
    }

    public function restore(Request $request, string $funcionario, FuncionarioRestoration $action): RedirectResponse
    {
        $funcionario = Funcionario::onlyTrashed()->findOrFail($funcionario);

        $action->execute(
            actor: $request->user(),
            funcionario: $funcionario
        );

        return redirect()->back()->with('success', 'Exclusao desfeita com sucesso.');
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
