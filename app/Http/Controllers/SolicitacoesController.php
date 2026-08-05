<?php

namespace App\Http\Controllers;

use App\Actions\Solicitacoes\SolicitacaoDecision;
use App\Http\Requests\Solicitacoes\SolicitacaoTransferRequest;
use Illuminate\Http\RedirectResponse;

class SolicitacoesController extends Controller
{
    public function storeTransferencia(SolicitacaoTransferRequest $request, SolicitacaoDecision $action): RedirectResponse
    {
        $action->solicitarTransferencia(
            actor: $request->user(),
            data: $request->validated(),
        );

        return redirect()
            ->route('dashboard.admin-setor')
            ->with('success', 'Solicitacao de transferencia criada com sucesso.');
    }

    public function execute(SolicitacaoDecision $action): RedirectResponse
    {
        return redirect()->route('dashboard');
    }
}
