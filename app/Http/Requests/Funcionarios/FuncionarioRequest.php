<?php

namespace App\Http\Requests\Funcionarios;

use Illuminate\Foundation\Http\FormRequest;

class FuncionarioRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'salary' => ['required', 'numeric', 'min:0'],
            'cargo_id' => ['required', 'integer', 'exists:cargos,id'],
        ];
    }
}
