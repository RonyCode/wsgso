<?php

declare(strict_types=1);

namespace Gso\Ws\Web\Controllers;

use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignIn\InputBoundaryUserAuthSignIn;
use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignIn\UserAuthSignInCase;
use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp\UserAuthSignUpCase;
use Gso\Ws\Web\Helper\ResponseError;
use Gso\Ws\Web\Presentation\UserPresentationRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

final class UserAuthController
{
    use ResponseError;

    public function __construct(
        private readonly UserPresentationRepository $usuarioAuthPresentation,
        private readonly UserAuthSignInCase $userAuthSignInCase,
    ) {
    }

    public function __invoke(Request $request, Response $response, array $args): Response
    {
        try {
            if (
                empty($request->getParsedBody()['email'])
                && empty(
                    $request->getParsedBody()['senha']
                )
            ) {
                throw new \RuntimeException('Parâmetros ausentes');
            }

            // PEGA OS HTTPs
            $email          = htmlentities($request->getParsedBody()['email']);
            $senha          = htmlentities($request->getParsedBody()['senha']);
            $isUserExternal = $request->getParsedBody()['is_user_external'] ?? 0;

            $inputBoundary = new InputBoundaryUserAuthSignIn($email, $senha, $isUserExternal);
            $output        = $this->userAuthSignInCase->execute($inputBoundary);

            if (null === $output->codUsuario) {
                return throw new \RuntimeException('Usuário ou senha incorreto, tente novamente!', 256 | 64);
            }

            $result = $this->usuarioAuthPresentation->outPut($output);
            $token  = $result['data']['token'];
            $result = json_encode($result, JSON_THROW_ON_ERROR | 64 | 256);
            $response->getBody()->write($result);

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withHeader('Authorization', $token)
                ->withStatus(202);
        } catch (\RuntimeException $e) {
            $result = [
                'status'  => 'failure',
                'code'    => 401,
                'message' => $e->getMessage(),
            ];
            $response->getBody()->write(json_encode($result, JSON_THROW_ON_ERROR | 64 | 256));

            return $response
                ->withHeader('Content-Type', 'application/json')
                ->withStatus(401);
        }
    }
}
