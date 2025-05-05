<?php

namespace App\Domain\Enums;

class ErrorContext
{
    const LOGIN = 'app.login';
    const REGISTER = 'app.register';
    const IMAGE = 'app.image';
    const AUTH_ACCESS_TOKEN = 'app.auth.access_token';
    const AUTH_REFRESH_TOKEN = 'app.auth.refresh_token';
    const INSTITUICAO = 'app.instituicao';
    const CURSO = 'app.curso';
    const USER = 'app.user';
    const PUBLICACAO = 'app.publicacao';
    
    const CONTEXTS = [
        self::AUTH_ACCESS_TOKEN  => 'Erro ao gerar o Access Token.',
        self::AUTH_REFRESH_TOKEN => 'Erro ao gerar o Refresh Token.',
        self::IMAGE => 'Erro ao processar solicitação de imagem.',
        self::LOGIN => 'Erro ao efetuar o login do usuário',
        self::REGISTER => 'Erro ao efetuar o registro do usuário.',
        self::INSTITUICAO => 'Erro ao registrar/atualizar uma instituição.',
        self::CURSO => 'Erro ao registrar/atualizar um curso.',
        self::USER => 'Erro ao registrar/atualizar um usuário.',
        self::PUBLICACAO => 'Erro ao registrar/atualizar uma publicação.'
    ];

    public static function getDescription($context): mixed
    {
        return self::CONTEXTS[$context] ?? 'No context :(';
    }
}
