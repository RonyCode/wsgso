<?php

namespace Gso\Ws\Web\Controllers;

use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignIn\UserAuthSignInCase;
use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp\InputBoundaryUserAuthSignUp;
use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp\UserAuthSignUpCase;
use Gso\Ws\Web\Helper\ResponseError;
use Gso\Ws\Web\Presentation\UserPresentationRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

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
                empty($args['token'])
            ) {
                throw new \RuntimeException('Parâmetros ausentes');
            }

            // PEGA OS HTTPs
            $email = htmlentities($args['token']);


            $inputBoundary = new InputBoundaryUserAuthSignUp($email);
            $output        = $this->usuarioAuthCase->execute($inputBoundary);
//
//            if (null === $output->codUsuario) {
//                return throw new \RuntimeException('Usuário ou senha incorreto, tente novamente!', 256 | 64);
//            }
//
//            $result = $this->usuarioAuthPresentation->outPut($output);
//            $token  = $result['data']['token'];
//            $result = json_encode($result, JSON_THROW_ON_ERROR | 64 | 256);
            $response->getBody()->write(json_encode(['email' => $email]), JSON_THROW_ON_ERROR | 64 | 256);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(202);
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
