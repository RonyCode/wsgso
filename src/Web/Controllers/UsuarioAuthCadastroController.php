<?php

namespace Gso\Ws\Web\Controllers;

use Fiber;
use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignIn\UserAuthSignInCase;
use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp\InputBoundaryUserAuthSignUp;
use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp\UserAuthSignUpCase;
use Gso\Ws\Context\User\Domains\User\Events\PublishEmailSendedSignUpUserAuth;
use Gso\Ws\Context\User\Domains\User\Events\UserSendedEmailSignUp;
use Gso\Ws\Context\User\Domains\User\Events\UserSignedEvent;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Web\Helper\ResponseError;
use Gso\Ws\Web\Message\Builder;
use Gso\Ws\Web\Presentation\UserPresentationRepository;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use React\EventLoop\Loop;
use React\Promise\Deferred;

use function DI\string;
use function React\Async\async;
use function React\Async\await;

class UsuarioAuthCadastroController
{
    use ResponseError;

    private $output;

    public function __construct(
        private readonly UserAuthSignUpCase $usuarioAuthCase,
        private readonly UserPresentationRepository $usuarioAuthPresentation
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

            $output = $this->usuarioAuthCase->execute($inputBoundary);

            if (null === $output->token) {
                throw new \RuntimeException('Erro ao cadastrar novo usuário!', 256 | 64);
            }


            $resut = [
                "status"  => 'success',
                "code"    => 200,
                "data"    => [
                    "email" => (string)$output->email,
                    "token" => $output->token
                ],
                "message" => 'Email enviado com sucesso!'
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
