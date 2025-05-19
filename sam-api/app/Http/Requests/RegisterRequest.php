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
            'id_instituicao.required' => 'O atributo id_instituicao é obrigatório.',
            'id_instituicao.integer' => 'O atributo id_instituicao deve ser um valor inteiro',

            'id_curso.required' => 'O atributo id_curso é obrigatório.',
            'id_curso.integer' => 'O atributo id_curso deve ser um valor inteiro',

            'name.required' => 'O atributo nome é obrigatório.',
            'name.string' => 'O atributo nome deve ser uma string.',
            'name.max' => 'O atributo nome deve conter no máximo 255 caracteres',

            'email.required' => 'O atributo email é obrigatório.',
            'email.email' => 'O atributo email é inválido.',

            'password.required' => 'O atributo password é obrigatório.',
            'password.min' => 'O atributo password deve ter no mínimo 6 caracteres.',
            'password.confirmed' => 'As senhas (password e password_confirmation) não coincidem.',
            
            'password_confirmation.required' => 'O atributo password_confirmation obrigatório.',
            'password_confirmation.min' => 'O atributo password_confirmation deve ter no mínimo 6 caracteres.',
        ];
    }
}