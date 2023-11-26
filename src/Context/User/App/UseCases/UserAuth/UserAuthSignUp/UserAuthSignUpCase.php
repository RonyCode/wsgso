<?php

namespace Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp;

use Gso\Ws\Context\User\Domains\User\Events\PublishEmailSendedSignUpUserAuth;
use Gso\Ws\Context\User\Domains\User\Events\PublishLogUserSigned;
use Gso\Ws\Context\User\Domains\User\Events\UserSendedEmailSignUp;
use Gso\Ws\Context\User\Domains\User\Events\UserSignedEvent as UserSignInEvent;
use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\UserAuth;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Web\Helper\EmailHandler;
use Gso\Ws\Web\Helper\JwtHandler;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

use function DI\string;

class UserAuthSignUpCase
{
    use ResponseError;

    public function __construct(
        public readonly UserAuthRepositoryInterface $usuarioAuthRepository,
        public readonly PublishEvents $publishEvents,
    ) {
    }


    public function execute(InputBoundaryUserAuthSignUp $inputValues): OutputBoundaryUserAuthSignUp
    {
        try {
            $userAuthFounded = $this->usuarioAuthRepository->getUserAuthByEmail($inputValues->email);
            //            VERIFICA SE JÁ EXISTE USUÁRIO CADASTRADO
            if (! empty($userAuthFounded->id)) {
                throw new RuntimeException('Usuário com email já cadastrado!');
            }

            // CRIA TOKEN PARA CADASTRO POR EMAIL
            $token = (new JwtHandler(900))->jwtEncode(getenv('ISS'), [
                'email' => $inputValues->email,
            ]);


            //PREPARA TOKEN PARA ENVIAR COM URL VALIDA AO EMAIL
            $tokenReplaced = str_replace('.', '+', $token);

            // MONTA E ENVIA EMAIL
            $emaillHandle   = new EmailHandler();
            $tituloEmail    = "Confirmação de Cadastro";
            $messageContent =
                "Email para confirmação de cadastro, por favor clique no link abaixo para finalizar seu cadastro.";
            $linkEmail      = getenv('URL_FRONTEND') . '/cadastro-usuario/' . $tokenReplaced;

            $publishEvents = new PublishEvents();
            $publishEvents->addListener(new PublishEmailSendedSignUpUserAuth());
            $publishEvents->publish(new UserSendedEmailSignUp(new Email($inputValues->email)));

            fastcgi_finish_request();

            $emailDestination = new Email($inputValues->email);
            $result           = $emaillHandle->sendMessage(
                $emailDestination,
                $tituloEmail,
                $messageContent,
                $linkEmail,
                true,
            );
            if (! $result) {
                throw new RuntimeException('Erro ao enviar email!');
            }


            return new OutputBoundaryUserAuthSignUp(
                (new Email($inputValues->email)),
                $token,
            );
        } catch (RuntimeException $e) {
            $this->responseCatchError($e->getMessage(), 400);
        }
    }
}
