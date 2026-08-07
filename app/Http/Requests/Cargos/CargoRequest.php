<?php

namespace App\Http\Requests\Cargos;

use Illuminate\Foundation\Http\FormRequest;

class CargoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:cargos,name'],
            'description' => ['nullable', 'string', 'max:2000'],
            'hierarchy' => ['required', 'integer', 'min:1'],
        ];
    }
}
