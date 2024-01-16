<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Providers\CreditosServiceProvider;
use App\Models\CreditoModel;
use Illuminate\Support\Facades\Session;

class CreditoController extends Controller
{
    public function consultaOfertaDeCredito(Request $request) {
        $cpf = $request->input('cpf');
        $consultaCreditoService = new CreditosServiceProvider();
        $resultadoConsulta = $consultaCreditoService->consultaOfertaDeCredito($cpf);

        $consultaCreditoModel = new CreditoModel();
        $consultaCreditoModel->save();

        return response()->json($resultadoConsulta, 200);
    }

    public function simulacaoOfertaDeCredito(Request $request) {
        $cpf = $request->input('cpf');
        $instituicaoId = $request->input('instituicao_id');
        $codModalidade = $request->input('codModalidade');
        
        $simulacaoOfertaService = new CreditosServiceProvider();
        $resultadoSimulacao = $simulacaoOfertaService->simulacaoOfertaDeCredito($cpf, $instituicaoId, $codModalidade);
        
        return response()->json($resultadoSimulacao, 200);
    }

    public function relatorio(Request $request) {
        $consultaCreditoModel = CreditoModel::groupBy('instituicaoFinanceira')
        ->selectRaw('count(instituicaoFinanceira) as total, instituicaoFinanceira')
        ->get();

        return response()->json($consultaCreditoModel, 200);
    }

    public function ofertasMaisVantajosas(Request $request) {
        $cpf = $request->input('cpf');

        $consultaCreditoService = new CreditosServiceProvider();
        $simulacaoOfertaService = new CreditosServiceProvider();

        $resultadoConsulta = $consultaCreditoService->consultaOfertaDeCredito($cpf);
        
        $ofertas = [];

        foreach ($resultadoConsulta['instituicoes'] as $instituicao) {
            $nomeInstituicao = $instituicao['nome'];
            $instituicaoId =  $instituicao['id'];

            foreach ($instituicao['modalidades'] as $modalidade) {
                $nomeModalidade = $modalidade['nome'];
                $codModalidade = $modalidade['cod'];

                $resultadoSimulacao = $simulacaoOfertaService->simulacaoOfertaDeCredito($cpf, $instituicaoId, $codModalidade);

                $valorSolicitado = $resultadoSimulacao['valorMax'];
                $taxaJuros = $resultadoSimulacao['jurosMes'];
                $qntParcelas = $resultadoSimulacao['QntParcelaMax'];
                $valorAPagar = $this->calcularValorAPagar($valorSolicitado, $taxaJuros, $qntParcelas);

                $ofertas[] = [
                    'instituicaoFinanceira' => $nomeInstituicao,
                    'modalidadeCredito' => $nomeModalidade,
                    'valorAPagar' => $valorAPagar,
                    'valorSolicitado' => $valorSolicitado,
                    'taxaJuros' => $taxaJuros,
                    'qntParcelas' => $qntParcelas,
                ];
            }
        }

        $this->salvarOfertasNoBanco($ofertas);

        return response()->json($this->ordenarOfertasPorTaxaDeJuros($ofertas), 200);
    }

    private function salvarOfertasNoBanco($ofertas) {
        foreach ($ofertas as $oferta) {
            $consultaCreditoModel = new CreditoModel();
            $consultaCreditoModel->instituicaoFinanceira = $oferta['instituicaoFinanceira'];
            $consultaCreditoModel->modalidadeCredito = $oferta['modalidadeCredito'];
            $consultaCreditoModel->valorAPagar = $oferta['valorAPagar'];
            $consultaCreditoModel->valorSolicitado = $oferta['valorSolicitado'];
            $consultaCreditoModel->taxaJuros = $oferta['taxaJuros'];
            $consultaCreditoModel->qntParcelas = $oferta['qntParcelas'];
            $consultaCreditoModel->save();
        }
    }

    private function ordenarOfertasPorTaxaDeJuros($ofertas) {
        usort($ofertas, function($a, $b) {
            return $a['taxaJuros'] <=> $b['taxaJuros'];
        });
        return $ofertas;
    }

    private function calcularValorAPagar($valorSolicitado, $taxaJuros, $qntParcelas) {
        $valorAPagar = $valorSolicitado * ((1 + $taxaJuros) ** $qntParcelas);
        return number_format($valorAPagar, 2, '.', '');
    }
}
