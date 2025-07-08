<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
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
                            <h2 style="color: #FFFFFF;">
                                {{ $message ?? 'Erro na verificação de e-mail.' }}
                            </h2>
                        </td>
                    </tr>

                    <tr>
                        <td align="left" style="font-weight: 100; font-size: 12px; opacity: 0.8; padding-top: 20px;">
                            <p>Obrigado por usar o Sam :)</p>
                            <strong>The Sam team</strong>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>
