<?php

namespace Gso\Ws\App\Controllers;

use Gso\Ws\App\Helper\ResponseError;
use Gso\App\App\UseCases\OcorrenciaAllCase\OcorrenciaAllCase;
use Gso\App\Infra\Interfaces\InterfacesPresentation\OcorrenciaAllPresentationInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class OcorrenciaGetAllController
{
    use ResponseError;


    public function __construct(
        private readonly OcorrenciaAllCase $ocorrenciaAllCase,
        private readonly OcorrenciaAllPresentationInterface $presentation,
    ) {

    }//end __construct()


    public function __invoke(Request $request, Response $response, array $args): Response
    {
        // try {
        // VALIDA OS INPUTS
        // if (empty($codOcorrencia)) {
        // throw new RuntimeException();
        // }
        //
        // $inputBoundary = new InputBoundary($codOcorrencia);
        // $output        = $this->ocorrenciaAllCase->handle($inputBoundary);
        //
        // $result = $this->presentation->outPut($output);
        // $response->getBody()->write($result);
        //
        // return $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        // } catch (RuntimeException) {
        // $response->getBody()->write(
        // $this->responseCatchError('Erro no controler, dados HTTPs ou Retorno do banco com erro!')
        // );
        // $response->withHeader('Content-Type', 'application/json')->withStatus(201);
        // }
        return $response->withHeader('Content-Type', 'application/json')->withStatus(201);

    }//end __invoke()


}//end class
