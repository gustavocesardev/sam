<?php

namespace App\Domain\Enums;

enum UF: string
{
    case AC = 'AC'; // Acre
    case AL = 'AL'; // Alagoas
    case AP = 'AP'; // Amapá
    case AM = 'AM'; // Amazonas
    case BA = 'BA'; // Bahia
    case CE = 'CE'; // Ceará
    case DF = 'DF'; // Distrito Federal
    case ES = 'ES'; // Espírito Santo
    case GO = 'GO'; // Goiás
    case MA = 'MA'; // Maranhão
    case MT = 'MT'; // Mato Grosso
    case MS = 'MS'; // Mato Grosso do Sul
    case MG = 'MG'; // Minas Gerais
    case PA = 'PA'; // Pará
    case PB = 'PB'; // Paraíba
    case PR = 'PR'; // Paraná
    case PE = 'PE'; // Pernambuco
    case PI = 'PI'; // Piauí
    case RJ = 'RJ'; // Rio de Janeiro
    case RN = 'RN'; // Rio Grande do Norte
    case RS = 'RS'; // Rio Grande do Sul
    case RO = 'RO'; // Rondônia
    case RR = 'RR'; // Roraima
    case SC = 'SC'; // Santa Catarina
    case SP = 'SP'; // São Paulo
    case SE = 'SE'; // Sergipe
    case TO = 'TO'; // Tocantins

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
