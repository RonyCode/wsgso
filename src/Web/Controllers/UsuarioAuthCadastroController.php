<?php

namespace Gso\Ws\Web\Controllers;

use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignIn\UserAuthSignInCase;
use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp\InputBoundaryUserAuthSignUp;
use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp\UserAuthSignUpCase;
use Gso\Ws\Web\Helper\ResponseError;
use Gso\Ws\Web\Presentation\UserPresentationRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use function DI\string;

class UsuarioAuthCadastroController
{
    use ResponseError;

    public function __construct(
        private UserAuthSignUpCase $usuarioAuthCase,
        private UserPresentationRepository $usuarioAuthPresentation
    ) {
    }


    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            if (
                empty($request->getParsedBody()['email'])
            ) {
                throw new \RuntimeException('Parâmetros ausentes');
            }

            // PEGA OS HTTPs
            $email = htmlentities($request->getParsedBody()['email']);

            $inputBoundary = new InputBoundaryUserAuthSignUp($email);
            $output        = $this->usuarioAuthCase->execute($inputBoundary);

            if (null === $output->token) {
                return throw new \RuntimeException('Erro ao cadastrar novo usuário!', 256 | 64);
            }

            $resut = [
                "status"  => 'success',
                "code"    => 200,
                "data"    => [
                    "email" => (string)$output->email,
                    "token" => $output->token
                ],
                "message" => 'Cadastrado com sucesso!'
            ];

            $response->getBody()->write(json_encode($resut), JSON_THROW_ON_ERROR | 64 | 256);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(200);
        } catch (\RuntimeException $e) {
            $result = [
                'status'  => 'failure',
                'code'    => 400,
                'message' => $e->getMessage(),
            ];
            $response->getBody()->write(json_encode($result, JSON_THROW_ON_ERROR | 64 | 256));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(400);
        }
    }
}
