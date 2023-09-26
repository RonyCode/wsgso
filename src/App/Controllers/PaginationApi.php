<?php

namespace Gso\Ws\App\Controllers;

use Gso\Ws\App\Helper\ResponseError;
use Gso\App\Infra\Interfaces\GlobalConnectionInterface;
use Gso\Ws\Infra\Repositories\RepositoriesModel\OcorrenciaRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PaginationApi
{
    use ResponseError;

    private Response $response;
    private Request $request;
    private array $args;

    public function __construct(
        private GlobalConnectionInterface $globalConnection,
        private OcorrenciaRepository $ocorrenciaRepository,
    ) {
    }


    public function __invoke()
    {
    }
}
