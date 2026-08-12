<?php

namespace App\Http\Controllers;

use App\Actions\Solicitacoes\SolicitacaoDecision;
use App\Enums\SolicitacaoStatus;
use App\Http\Requests\Solicitacoes\SolicitacaoAumentoRequest;
use App\Http\Requests\Solicitacoes\SolicitacaoPromocaoRequest;
use App\Http\Requests\Solicitacoes\SolicitacaoTransferRequest;
use App\Models\Solicitacao;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class SolicitacoesController extends Controller
{
    public function index(): View
    {
        Gate::authorize('viewSuperAdminDashboard', User::class);

        return view('solicitacoes.index', [
            'solicitacoesPendentes' => Solicitacao::query()->with(['funcionario', 'solicitadoPor', 'setorOrigem', 'setorDestino'])->where('status', SolicitacaoStatus::PENDENTE->value)->latest()->get(),
            'solicitacoesDecididas' => Solicitacao::query()->with(['funcionario', 'solicitadoPor', 'decididoPor', 'setorOrigem', 'setorDestino'])->whereIn('status', [
                SolicitacaoStatus::APROVADA->value,
                SolicitacaoStatus::REPROVADA->value,
            ])->latest('decidido_em')->latest()->get(),
        ]);
    }

    public function recebidas(): View
    {
        Gate::authorize('viewAdminSetorDashboard', User::class);

        $user = Auth::user();

        return view('solicitacoes.recebidas', [
            'solicitacoesRecebidas' => Solicitacao::query()
                ->with('funcionario')
                ->where('setor_destino_id', $user->setor_id)
                ->latest()
                ->get(),
        ]);
    }

    public function storeTransferencia(SolicitacaoTransferRequest $request, SolicitacaoDecision $action): RedirectResponse
    {
        $action->solicitarTransferencia(
            actor: $request->user(),
            data: $request->validated(),
        );

        return redirect()->route('dashboard.admin-setor')->with('success', 'Solicitacao de transferencia criada com sucesso.');
    }

    public function storeAumento(SolicitacaoAumentoRequest $request, SolicitacaoDecision $action): RedirectResponse
    {
        $action->solicitarAumento(
            actor: $request->user(),
            data: $request->validated(),
        );

        return redirect()->route('dashboard.admin-setor')->with('success', 'Solicitacao de aumento salarial criada com sucesso.');
    }

    public function storePromocao(SolicitacaoPromocaoRequest $request, SolicitacaoDecision $action): RedirectResponse
    {
        $action->solicitarPromocao(
            actor: $request->user(),
            data: $request->validated(),
        );

        return redirect()->route('dashboard.admin-setor')->with('success', 'Solicitacao de mudanca de cargo criada com sucesso.');
    }

    public function execute(SolicitacaoDecision $action): RedirectResponse
    {
        return redirect()->route('dashboard');
    }

    public function accept(Solicitacao $solicitacao, SolicitacaoDecision $action): RedirectResponse
    {
        $action->execute(
            actor: request()->user(),
            solicitacao: $solicitacao,
            justificativa: 'Solicitacao aceita pelo admin de setor.',
        );

        return redirect()
            ->back()
            ->with('success', 'Solicitacao aceita com sucesso.');
    }

    public function reject(Solicitacao $solicitacao, SolicitacaoDecision $action): RedirectResponse
    {
        $action->reject(
            actor: request()->user(),
            solicitacao: $solicitacao,
            justificativa: 'Solicitacao recusada pelo admin de setor.',
        );

        return redirect()->back()->with('success', 'Solicitacao recusada com sucesso.');
    }

    public function destroy(Solicitacao $solicitacao, SolicitacaoDecision $action): RedirectResponse
    {
        $action->undo(
            actor: request()->user(),
            solicitacao: $solicitacao,
        );

        return redirect()->back()->with('success', 'Solicitacao desfeita com sucesso.');
    }
}
