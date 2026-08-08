<?php

namespace App\Http\Requests\Solicitacoes;

use App\Enums\SolicitacaoStatus;
use App\Enums\SolicitacaoTipo;
use App\Models\Funcionario;
use App\Models\Solicitacao;
use Illuminate\Foundation\Http\FormRequest;

class SolicitacaoPromocaoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'funcionario_id' => ['required', 'integer', 'exists:funcionarios,id'],
            'cargo_proposto_id' => ['required', 'integer', 'exists:cargos,id'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $funcionario = Funcionario::query()->find($this->input('funcionario_id'));

            if ($funcionario && $this->user()?->setor_id !== $funcionario->setor_id) {
                $validator->errors()->add('funcionario_id', 'Este funcionario nao pertence ao seu setor.');
            }

            if ($funcionario && (int) $this->input('cargo_proposto_id') === $funcionario->cargo_id) {
                $validator->errors()->add('cargo_proposto_id', 'Escolha um cargo diferente do cargo atual.');
            }

            if ($funcionario && Solicitacao::query()
                ->where('tipo', SolicitacaoTipo::PROMOCAO->value)
                ->where('status', SolicitacaoStatus::PENDENTE->value)
                ->where('funcionario_id', $funcionario->id)
                ->exists()) {
                $validator->errors()->add('funcionario_id', 'Este funcionario ja possui uma mudanca de cargo pendente.');
            }
        });
    }
}
