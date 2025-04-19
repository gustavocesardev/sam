<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id_instituicao' => ['required', 'integer'],
            'id_curso' => ['required', 'integer'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6', 'confirmed'],
            'password_confirmation' => ['required', 'min:6']
        ];
    }

    public function messages(): array
    {
        return [
            'id_instituicao.required' => 'O id_instituicao é obrigatório.',
            'id_instituicao.integer' => 'O id_instituicao deve ser um valor inteiro',

            'id_curso.required' => 'O id_curso é obrigatório.',
            'id_curso.integer' => 'O id_curso deve ser um valor inteiro',

            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'O nome deve ser uma string.',
            'name.max' => 'O nome deve conter no máximo 255 caracteres',

            'email.required' => 'O e-mail é obrigatório.',
            'email.email' => 'Insira um e-mail válido.',

            'password.required' => 'A senha é obrigatória.',
            'password.min' => 'A senha deve ter no mínimo 6 caracteres.',
            'password.confirmed' => 'As senhas não coincidem.',
            
            'password_confirmation.required' => 'A confirmação de senha é obrigatória.',
            'password_confirmation.min' => 'A confirmação de senha deve ter no mínimo 6 caracteres.',
        ];
    }
}
