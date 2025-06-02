<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verificação de E-mail</title>
</head>
<body style="font-family: 'Roboto', sans-serif; background-color: #090D1D; padding: 20px; color: #FFFFFF; text-align: left;">
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td align="left">
                <table width="600" bgcolor="#090D1D" style="color: #FFFFFF; padding: 20px; border-radius: 8px;">
                    <tr>
                        <td align="left" style="font-family: 'Suranna', serif; font-size: 24px; padding-bottom: 20px;">
                            <span style="font-size: 2em;">SAM</span>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-weight: 300; font-size: 16px;">
                            <h2 style="color: #FFFFFF;">Olá, {{ $dados['name'] }}</h2>
                            <p style="color: #FFFFFF;">Estamos muito felizes pelo seu registro! Para começar a interagir no aplicativo, ative sua conta clicando no botão abaixo.</p>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="padding-top: 20px;">
                            <table cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="left" bgcolor="#3A5781" style="border-radius: 5px;">
                                        <a href="{{ $dados['verification_url'] }}" 
                                           style="display: inline-block; padding: 10px 20px; color: #ffffff; text-decoration: none; font-weight: bold;">
                                            Verificar agora
                                        </a>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-weight: 100; font-size: 12px; opacity: 0.8; padding-top: 20px;">
                            <p>Bem-vindo(a) ao Sam :)</p>
                            <strong>The Sam team</strong>
                        </td>
                    </tr>
                    <tr>
                        <td align="left" style="font-weight: 100; font-size: 12px; opacity: 0.8; padding-top: 20px;">
                            Se você não solicitou este e-mail, ignore esta mensagem.
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
