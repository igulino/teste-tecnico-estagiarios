<?php

namespace App\Enums;

enum SolicitacaoTipo: string
{
    case TRANSFERENCIA = 'transferencia';
    case AUMENTO = 'aumento';
    case PROMOCAO = 'promocao';
}