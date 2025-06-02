<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'email' => 'required|email',
            'password' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'email.required' => 'O atributo email é obrigatório.',
            'email.email' => 'O atributo email informado é inválido.',
            'password.required' => 'O atributo password é obrigatório.',
        ];
    }
}
