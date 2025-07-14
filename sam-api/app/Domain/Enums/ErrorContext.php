<?php

namespace App\Domain\Enums;

class ErrorContext
{
    const LOGIN = 'app.login';
    const REGISTER = 'app.register';
    const EMAIL_VERIFY = 'app.email_verify';
    const FILE = 'app.file';
    const AUTH_ACCESS_TOKEN = 'app.auth.access_token';
    const AUTH_REFRESH_TOKEN = 'app.auth.refresh_token';
    const INSTITUICAO = 'app.instituicao';
    const CURSO = 'app.curso';
    const USER = 'app.user';
    const PUBLICACAO = 'app.publicacao';
    const GRUPO_ESTUDO = 'app.grupo_estudo';
    const GRUPO_ESTUDO_PUBLICACAO = 'app.grupo_estudo.publicaco';
    const GRUPO_ESTUDO_MEMBRO = 'app.grupo_estudo.membro';
    const FORMULARIO = 'app.formulario';
    
    const CONTEXTS = [
        self::AUTH_ACCESS_TOKEN  => 'Erro ao gerar o Access Token.',
        self::AUTH_REFRESH_TOKEN => 'Erro ao gerar o Refresh Token.',
        self::FILE => 'Erro ao processar solicitação de arquivo.',
        self::LOGIN => 'Erro ao efetuar o login do usuário',
        self::EMAIL_VERIFY => 'Erro ao efetuar a verificação do e-mail.',
        self::REGISTER => 'Erro ao efetuar o registro do usuário.',
        self::INSTITUICAO => 'Erro no processamento ou serviço referente à instituição.',
        self::CURSO => 'Erro no processamento ou serviço referente ao curso.',
        self::USER => 'Erro no processamento ou serviço referente ao usuário.',
        self::PUBLICACAO => 'Erro no processamento ou serviço referente à publicação.',
        self::GRUPO_ESTUDO => 'Erro no processamento ou serviço referente ao grupo de estudo.',
        self::GRUPO_ESTUDO_PUBLICACAO => 'Erro no processamento ou serviço referente à publicação do grupo de estudo.',
        self::GRUPO_ESTUDO_MEMBRO => 'Erro no processamento ou serviço referente ao membro no grupo de estudo.',
        self::FORMULARIO => 'Erro no processamento ou serviço referente ao formulário.'
    ];

    public static function getDescription($context): mixed
    {
        return self::CONTEXTS[$context] ?? 'No context :(';
    }
}
