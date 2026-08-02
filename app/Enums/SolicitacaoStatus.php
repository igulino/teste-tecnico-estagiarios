<?php

namespace App\Enums;

enum SolicitacaoStatus: string
{
    case PENDENTE = 'pendente';
    case APROVADA = 'aprovada';
    case REPROVADA = 'reprovada';
}