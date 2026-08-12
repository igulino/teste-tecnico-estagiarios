<?php

namespace App\Http\Controllers;

use App\Enums\SolicitacaoStatus;
use App\Enums\SolicitacaoTipo;
use App\Enums\UserRole;
use App\Models\Cargo;
use App\Models\Funcionario;
use App\Models\Setor;
use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): RedirectResponse
    {
        if (Gate::allows('viewSuperAdminDashboard', User::class)) {
            return redirect()->route('dashboard.super-admin');
        }

        if (Gate::allows('viewAdminSetorDashboard', User::class)) {
            return redirect()->route('dashboard.admin-setor');
        }

        abort(403);
    }

    public function superAdmin(): View
    {
        Gate::authorize('viewSuperAdminDashboard', User::class);

        return view('dashboard.super-admin', [
            'setores' => Setor::query()->with([
                'admin' => fn ($query) => $query->where('role', UserRole::ADMIN_SETOR),
                'funcionarios' => fn ($query) => $query->orderBy('name'),
            ])->orderBy('name')->get(),
            'totalFuncionarios' => Funcionario::query()->count(),
            'funcionariosExcluidos' => Funcionario::onlyTrashed()->count(),
            'solicitacoesPendentes' => Solicitacao::query()->where('status', 'pendente')->count(),
            'totalAdminsSetor' => User::query()->where('role', UserRole::ADMIN_SETOR)->count(),
        ]);
    }

    public function adminSetor(): View
    {
        Gate::authorize('viewAdminSetorDashboard', User::class);

        $user = Auth::user();

        return view('dashboard.admin-setor', [
            'setor' => $user->setor,
            'setores' => Setor::query()->where('id', '!=', $user->setor_id)->orderBy('name')->get(),
            'cargos' => Cargo::query()->orderBy('hierarchy')->orderBy('name')->get(),
            'funcionarios' => Funcionario::query()->with('cargo')->where('setor_id', $user->setor_id)->orderBy('name')->get(),
            'funcionariosComTransferenciaPendente' => Solicitacao::query()
                ->where('tipo', SolicitacaoTipo::TRANSFERENCIA->value)
                ->where('status', SolicitacaoStatus::PENDENTE->value)
                ->where('setor_origem_id', $user->setor_id)
                ->pluck('funcionario_id')
                ->all(),
            'solicitacoesFeitas' => Solicitacao::query()->with('funcionario')->where('solicitado_por_user_id', $user->id)->latest()->get(),
            'possuiSolicitacoesRecebidasPendentes' => Solicitacao::query()->where('setor_destino_id', $user->setor_id)->where('status', SolicitacaoStatus::PENDENTE->value)->exists(),
            'totalFuncionarios' => Funcionario::query()->where('setor_id', $user->setor_id)->count(),
            'solicitacoesPendentes' => Solicitacao::query()->where('setor_aprovador_id', $user->setor_id)->where('status', 'pendente')->count(),
            'solicitacoesDecididas' => Solicitacao::query()->where('decidido_por_user_id', $user->id)->count(),
        ]);
    }
}
