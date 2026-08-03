<?php

namespace App\Http\Requests\Setores;

use Illuminate\Foundation\Http\FormRequest;

class SetoresRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:setores,name'],

            'description' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'admin_nome' => [
                'required',
                'string',
                'max:255',
            ],

            'admin_email' => [
                'required',
                'email',
                'max:255',
                'unique:users,email',
            ],

            'admin_password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
        ];
    }
}
