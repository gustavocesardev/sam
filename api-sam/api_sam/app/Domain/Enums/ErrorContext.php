<?php

namespace App\Domain\Enums;

class ErrorContext
{
    const LOGIN = 'app.login';
    const AUTH_ACCESS_TOKEN = 'app.auth.access_token';
    const AUTH_REFRESH_TOKEN = 'app.auth.refresh_token';

    const CADASTRO_INSTITUICAO = 'app.instituicao';
    const CADASTRO_CURSO = 'app.curso';
    
    const CONTEXTS = [
        self::LOGIN => 'Erro ao efetuar o login do usuário',
        self::CADASTRO_INSTITUICAO => 'Erro ao cadastrar/atualizar uma instituição.',
        self::CADASTRO_CURSO => 'Erro ao cadastrar/atualizar um curso.',
        self::AUTH_ACCESS_TOKEN => 'Erro ao gerar o Access Token.',
        self::AUTH_REFRESH_TOKEN => 'Erro ao gerar o Refresh Token.',
    ];

    public static function getDescription($context): mixed
    {
        return self::CONTEXTS[$context] ?? 'No context :(';
    }
}
