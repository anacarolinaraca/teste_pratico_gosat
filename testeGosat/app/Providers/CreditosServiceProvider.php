<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Http;


class CreditosServiceProvider
{
    public function consultaOfertaDeCredito($cpf) {
        $baseURL = getenv('API_GOSAT');
        $response = Http::post($baseURL . '/credito', [
            'cpf' => $cpf,
        ]);

        $apiArray = $response->json();

        return $apiArray;
    }

    public function simulacaoOfertaDeCredito($cpf, $instituicaoId, $codModalidade) {
        $baseURL = getenv('API_GOSAT');

        $response = Http::post($baseURL . '/oferta', [
            'cpf' => $cpf,
            'instituicao_id' => $instituicaoId,
            'codModalidade' => $codModalidade
        ]);

        $apiArray = $response->json();
        return $apiArray;
    }
}
