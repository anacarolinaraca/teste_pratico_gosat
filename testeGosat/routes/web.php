<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CreditoController;

Route::get('/credito', [CreditoController::class, 'consultaOfertaDeCredito']);
Route::get('/oferta', [CreditoController::class, 'simulacaoOfertaDeCredito']);
Route::get('/relatorio', [CreditoController::class, 'relatorio']);
Route::get('/ofertas-mais-vantajosas', [CreditoController::class, 'ofertasMaisVantajosas']);
