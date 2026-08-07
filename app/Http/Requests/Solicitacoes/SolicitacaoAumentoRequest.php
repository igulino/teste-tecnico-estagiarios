<?php

namespace App\Http\Requests\Solicitacoes;

use App\Enums\SolicitacaoStatus;
use App\Enums\SolicitacaoTipo;
use App\Models\Funcionario;
use App\Models\Solicitacao;
use Illuminate\Foundation\Http\FormRequest;

class SolicitacaoAumentoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'funcionario_id' => ['required', 'integer', 'exists:funcionarios,id'],
            'salario_proposto' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $funcionario = Funcionario::query()->find($this->input('funcionario_id'));

            if ($funcionario && $this->user()?->setor_id !== $funcionario->setor_id) {
                $validator->errors()->add('funcionario_id', 'Este funcionario nao pertence ao seu setor.');
            }

            if ($funcionario && (float) $this->input('salario_proposto') <= (float) $funcionario->salary) {
                $validator->errors()->add('salario_proposto', 'Informe um salario maior que o salario atual.');
            }

            if ($funcionario && Solicitacao::query()
                ->where('tipo', SolicitacaoTipo::AUMENTO->value)
                ->where('status', SolicitacaoStatus::PENDENTE->value)
                ->where('funcionario_id', $funcionario->id)
                ->exists()) {
                $validator->errors()->add('funcionario_id', 'Este funcionario ja possui um aumento salarial pendente.');
            }
        });
    }
}
