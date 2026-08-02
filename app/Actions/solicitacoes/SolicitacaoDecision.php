<?php
use App\Enums\SolicitacaoTipo;
use App\Models\User;

use App\Models\Solicitacao;

class SolicitacaoDecision {
    
    public function execute(User $actor,Solicitacao $solicitacao,string $justificativa): void {
        Gate::forUser($actor)->authorize('decide', $solicitacao);

        DB::transaction(function () use ($actor, $solicitacao,$justificativa) {
            $solicitacao = Solicitacao::query()
                ->lockForUpdate()
                ->findOrFail($solicitacao->id);

            if (
                $solicitacao->status
                !== SolicitacaoStatus::PENDENTE
            ) {
                throw new SolicitacaoJaDecididaException();
            }

            match ($solicitacao->tipo) {
                SolicitacaoTipo::TRANSFERENCIA =>
                    $this->aplicarTransferencia(
                        $solicitacao,
                        $actor,
                    ),

                SolicitacaoTipo::AUMENTO =>
                    $this->aplicarAumento(
                        $solicitacao,
                        $actor,
                    ),

                SolicitacaoTipo::PROMOCAO =>
                    $this->aplicarPromocao(
                        $solicitacao,
                        $actor,
                    ),
            };

            $solicitacao->update([
                'status' => SolicitacaoStatus::APROVADA,
                'decidido_por_user_id' => $actor->id,
                'justificativa_decisao' => $justificativa,
                'decidido_em' => now(),
            ]);
        });
    }
}