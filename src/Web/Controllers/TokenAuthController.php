<?php

declare(strict_types=1);

namespace Gso\Ws\Web\Controllers;

use Gso\Ws\App\UseCases\TokenUser\GetTokenByCodUser\InputBoundaryTokenByCodUsuario;
use Gso\Ws\App\UseCases\TokenUser\GetTokenByCodUser\TokenByCodUser;
use Gso\Ws\Infra\Repositories\RepositoriesPresentation\TokenPresentation;
use Gso\Ws\Web\Helper\ResponseError;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class TokenAuthController
{
    use ResponseError;

    public function __construct(
        private readonly TokenPresentation $tokenManagerPresentation,
        private readonly TokenByCodUser $tokenManagerByCodUsuarioCase,
    ) {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            if (empty($args['token'])) {
                throw new \RuntimeException('Parâmetros para token ausentes');
            }

            // PEGA OS HTTPs
            $tokenUsuario = htmlentities($args['token']);

            $inputBoundary = new InputBoundaryTokenByCodUsuario($tokenUsuario);
            $output = $this->tokenManagerByCodUsuarioCase->handle($inputBoundary);

            if (null === $output->codUsuario) {
                return throw new \RuntimeException('Erro ao retornar Token, tente novamente', 256 | 64);
            }

            $result = $this->tokenManagerPresentation->outPut($output);

            $token = $result['token'];
            $result = json_encode($result, JSON_THROW_ON_ERROR | 64 | 256);
            $response->getBody()->write($result);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Authorization', $token)
                ->withStatus(202)
            ;
        } catch (\RuntimeException $e) {
            $result = [
                'status' => 'failure',
                'code' => 404,
                'message' => $e->getMessage(),
            ];
            $response->getBody()->write(json_encode($result, JSON_THROW_ON_ERROR | 64 | 256));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401)
            ;
        }
    }
}
