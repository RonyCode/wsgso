<?php

declare(strict_types=1);

namespace Gso\Ws\App\Controllers;

use Gso\Ws\App\Helper\ResponseError;
use Gso\App\App\UseCases\UsuarioAuthCase\InputBoundaryUsuarioAuth;
use Gso\App\App\UseCases\UsuarioAuthCase\UsuarioAuthCase;
use Gso\App\Infra\Repositories\RepositoriesPresentation\UsuarioAuthPresentation;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class UsuarioAuthController
{
    use ResponseError;

    public function __construct(
        private readonly UsuarioAuthPresentation $usuarioAuthPresentation,
        private readonly UsuarioAuthCase $usuarioAuthCase,
    ) {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            if (
                empty($request->getParsedBody()['email'])
                && empty(
                    $request->getParsedBody()['senha']
                )) {
                throw new \RuntimeException('Parâmetros ausentes');
            }

            // PEGA OS HTTPs
            $email = htmlentities($request->getParsedBody()['email']);
            $senha = htmlentities($request->getParsedBody()['senha']);
            $nome = htmlentities($request->getParsedBody()['nome']);
            $image = htmlentities($request->getParsedBody()['image']);
            $isUserExterno = $request->getParsedBody()['is_user_externo'];

            $inputBoundary = new InputBoundaryUsuarioAuth($email, $senha, $nome, $image, $isUserExterno);
            $output = $this->usuarioAuthCase->handle($inputBoundary);

            if (null === $output->codUsuario) {
                return throw new \RuntimeException('Usuário ou senha incorreto, tente novamente', 256 | 64);
            }

            $result = $this->usuarioAuthPresentation->outPut($output);
            $token = $result['data']['token'];
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
                'code' => 401,
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
