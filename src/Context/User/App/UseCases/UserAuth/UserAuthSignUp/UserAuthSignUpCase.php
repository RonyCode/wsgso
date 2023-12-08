<?php

namespace Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp;

use Gso\Ws\Context\User\Domains\User\Events\PublishEmailSendedSignUpUserAuth;
use Gso\Ws\Context\User\Domains\User\Events\UserSendedEmailSignUp;
use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Web\Helper\EmailHandler;
use Gso\Ws\Web\Helper\JwtHandler;
use Gso\Ws\Web\Helper\ResponseError;
use React\EventLoop\Loop;
use RuntimeException;

class UserAuthSignUpCase
{
    use ResponseError;

    public function __construct(
        public readonly UserAuthRepositoryInterface $usuarioAuthRepository,
        public readonly PublishEvents $publishEvents,
    ) {
    }


    public function execute(InputBoundaryUserAuthSignUp $inputValues): OutputBoundaryUserAuthSignUp|array
    {
        try {
            $userAuthFounded = $this->usuarioAuthRepository->getUserAuthByEmail($inputValues->email);
            //            VERIFICA SE JÁ EXISTE USUÁRIO CADASTRADO
            if (! empty($userAuthFounded->id)) {
                throw new RuntimeException('Usuário com email já cadastrado!', 256 | 64);
            }

            // CRIA TOKEN PARA CADASTRO POR EMAIL
            $token = (new JwtHandler(900))->jwtEncode(getenv('ISS'), [
                'email' => $inputValues->email,
            ]);


            //PREPARA TOKEN PARA ENVIAR COM URL VALIDA AO EMAIL
            $tokenReplaced = str_replace('.', '+', $token);

            //CRIA EVENTO E ENVIA MENSAGEM PARA RABBITMQ
            $publishEvents = new PublishEvents();
            $publishEvents->addListener(new PublishEmailSendedSignUpUserAuth());
            $publishEvents->publish(new UserSendedEmailSignUp(new Email($inputValues->email)));


            Loop::addTimer(0.1, static function () use ($inputValues, $tokenReplaced) {
                fastcgi_finish_request();

                $emaillHandle     = new EmailHandler();
                $tituloEmail      = "Confirmação de Cadastro";
                $messageContent   =
                    "Email para confirmação de cadastro, por favor clique no link abaixo para finalizar seu cadastro.
                Este link expira em 15 minutos.";
                $linkEmail        = getenv('URL_FRONTEND') . '/cadastro-usuario/' . $tokenReplaced;
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
            });


            return new OutputBoundaryUserAuthSignUp(
                (new Email($inputValues->email)),
                $token,
            );
        } catch (RuntimeException $e) {
            $this->responseCatchError($e->getMessage());
        }
    }
}
