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
    const GRUPO_ESTUDO = 'app.grupo_estudo';
    const GRUPO_ESTUDO_PUBLICACAO = 'app.grupo_estudo.publicaco';
    const GRUPO_ESTUDO_MEMBRO = 'app.grupo_estudo.membro';
    
    const CONTEXTS = [
        self::AUTH_ACCESS_TOKEN  => 'Erro ao gerar o Access Token.',
        self::AUTH_REFRESH_TOKEN => 'Erro ao gerar o Refresh Token.',
        self::IMAGE => 'Erro ao processar solicitação de imagem.',
        self::LOGIN => 'Erro ao efetuar o login do usuário',
        self::REGISTER => 'Erro ao efetuar o registro do usuário.',
        self::INSTITUICAO => 'Erro ao registrar/atualizar uma instituição.',
        self::CURSO => 'Erro ao registrar/atualizar um curso.',
        self::USER => 'Erro ao registrar/atualizar um usuário.',
        self::PUBLICACAO => 'Erro ao registrar/atualizar uma publicação.',
        self::GRUPO_ESTUDO => 'Erro ao registrar/atualizar um grupo de estudo',
        self::GRUPO_ESTUDO_PUBLICACAO => 'Erro ao registrar/atualizar uma publicação do grupo de estudo',
        self::GRUPO_ESTUDO_MEMBRO => 'Erro ao registrar um membro no grupo de estudo'
    ];

    public static function getDescription($context): mixed
    {
        return self::CONTEXTS[$context] ?? 'No context :(';
    }
}
