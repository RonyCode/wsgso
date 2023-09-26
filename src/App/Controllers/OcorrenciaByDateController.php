<?php

namespace Gso\Ws\App\Controllers;

use Gso\Ws\App\Helper\ResponseError;
use Gso\App\App\UseCases\OcorrenciaByDateCase\OcorrenciaByDateCase;
use Gso\App\Infra\Interfaces\InterfacesPresentation\OcorrenciaByDatePresentationInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OcorrenciaByDateController
{
    use ResponseError;

    public function __construct(
        private readonly OcorrenciaByDateCase $ocorrenciaByDateCase,
        private readonly OcorrenciaByDatePresentationInterface $presentation,
    ) {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        //        try {
        //            //PEGA OS HTTPs
        //            $dataFim = htmlspecialchars($request->getParsedBody()["data_fim"]);
        //            $dataInicio = htmlspecialchars($request->getParsedBody()["data_inicio"]);
        //            $page = $request;
        //            $itemsPerPage = 15;
        //
        //            //VALIDA OS INPUTS
        //            empty($dataInicio) ?? throw new RuntimeException();
        //            empty($dataFim) ?? throw new RuntimeException();
        //
        //
        //            $inputBoundary = new InputBoundaryOcorrenciaByDateCase($dataInicio, $dataFim, $page, $itemsPerPage);
        //
        //            $output = $this->ocorrenciaByDateCase->handle($inputBoundary);
        //            $result = $this->presentation->outPut($output);
        //
        //            $response->getBody()->write($result);
        //
        //            return $response
        //                ->withHeader('Content-Type', 'application/json')
        //                ->withStatus(201);
        //        } catch (RuntimeException) {
        //            $this->responseCatchError("Erro no controler, dados HTTPs ou Retorno do banco com erro!");
        //        }
        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(201)
        ;
    }
}
