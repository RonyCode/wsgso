<?php

namespace Gso\Ws\Web\Presentation;

use Gso\Ws\Context\User\App\UseCases\User\SignInUser\OutputBoundaryUserSignIn;
use Gso\Ws\Context\User\Domains\User\Events\LogUserSignedEvent;
use Gso\Ws\Context\User\Domains\User\Events\UserSignedEvent as UserSignInEvent;
use Gso\Ws\Context\User\Infra\User\Interface\UserPresentationInterface;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

class UserPresentationRepository implements UserPresentationInterface
{
    use ResponseError;

    public function outPut(OutputBoundaryUserSignIn $data): array
    {
        try {
                $data->codUsuario ?? throw new \RuntimeException();
            $publishEvents      = new PublishEvents();
            $logUserSignedEvent = new LogUserSignedEvent();

            $publishEvents->addListener($logUserSignedEvent);
            $publishEvents->publish(
                new UserSignInEvent(
                    $data->email,
                    $data->codUsuario
                )
            );

            return [
                'data' => [
                    'cod_usuario'        => $data->codUsuario,
                    'email'              => (string)$data->email,
                    'token'              => $data->token,
                    'refresh_token'      => $data->refreshToken,
                    'data_criacao_token' => $data->dataCriacaoToken,
                    'data_expirar_token' => $data->dataExpirarToken,
                ],
                'code'    => 202,
                'status'  => 'success',
                'message' => 'Auth realizado com sucesso',
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
