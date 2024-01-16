<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class CreditoModel extends Model
{
    protected $table = 'creditos';
    protected $attributes = [
        'instituicaoFinanceira' => '',
        'modalidadeCredito' => '',
        'valorAPagar' => 0,
        'valorSolicitado' => 0,
        'taxaJuros' => 0,
        'qntParcelas' => 0,
    ];
}
