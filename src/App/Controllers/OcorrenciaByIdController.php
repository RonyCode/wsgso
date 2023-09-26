<?php

namespace Gso\Ws\App\Controllers;

use Gso\Ws\App\Helper\ResponseError;
use Gso\App\App\UseCases\OcorrenciaByIdCase\OcorrenciaByIdCase;
use Gso\App\Infra\Interfaces\InterfacesPresentation\OcorrenciaByIdPresentationInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OcorrenciaByIdController
{
    use ResponseError;

    public function __construct(
        private readonly OcorrenciaByIdCase $ocorrenciaByIdCase,
        private readonly OcorrenciaByIdPresentationInterface $presentation,
    ) {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        //        try {
        //            // PEGA OS HTTPs
        //            $codOcorrencia = filter_var($request->getParsedBody()['cod_ocorrencia'], FILTER_VALIDATE_INT);
        //            // VALIDA OS INPUTS
        //            if (empty($codOcorrencia)) {
        //                throw new RuntimeException();
        //            }
        //
        //            $inputBoundary = new InputBoundary($codOcorrencia);
        //            $output        = $this->ocorrenciaByIdCase->handle($inputBoundary);
        //
        //            $result = $this->presentation->outPut($output);
        //            $response->getBody()->write($result);
        //
        //            return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        //        } catch (RuntimeException) {
        //            $this->responseCatchError('Erro no controler, dados HTTPs ou Retorno do banco com erro!');
        //        }//end try
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
    }
}
