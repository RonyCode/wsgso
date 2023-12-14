<?php

namespace Gso\Ws\Web\Presentation;

use Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignIn\OutputBoundaryUserAuthSignIn;
use Gso\Ws\Context\User\Domains\User\Events\command\UserSignedEvent as UserSignInEvent;
use Gso\Ws\Context\User\Domains\User\Events\publish\PublishLogUserSigned;
use Gso\Ws\Context\User\Infra\User\Interface\UserAuthPresentationInterface;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

class UserAuthPresentation implements UserAuthPresentationInterface
{
    use ResponseError;

    public function outPut(OutputBoundaryUserAuthSignIn $data): array
    {
        try {
                $data->codUsuario ?? throw new \RuntimeException();
            $publishEvents      = new PublishEvents();
            $logUserSignedEvent = new PublishLogUserSigned();

            $publishEvents->addListener($logUserSignedEvent);
            $publishEvents->publish(
                new UserSignInEvent(
                    new Email($data->email),
                    $data->codUsuario
                )
            );

            return [
                'data'    => [
                    'cod_usuario'        => $data->codUsuario,
                    'email'              => $data->email,
                    'token'              => $data->token,
                    'refresh_token'      => $data->refreshToken,
                    'data_criacao_token' => $data->dataCriacaoToken,
                    'data_expirar_token' => $data->dataExpirarToken,
                ],
                'code'    => 202,
                'status'  => 'success',
                'message' => 'Login realizado com sucesso',
            ];
        } catch (RuntimeException) {
            return [
                'code'    => 404,
                'status'  => 'failure',
                'message' => $this->responseCatchError('erro OutputBoundary'),
            ];
        }
    }
}
