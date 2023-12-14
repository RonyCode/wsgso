<?php

namespace Gso\Ws\Web\Presentation;

use Gso\Ws\Context\User\App\UseCases\User\Register\OutputBoundaryUserRegister;
use Gso\Ws\Context\User\Domains\User\Events\command\UserRegistredEvent;
use Gso\Ws\Context\User\Domains\User\Events\publish\PublishUserRegistred;
use Gso\Ws\Context\User\Infra\User\Interface\UserPresentationInterface;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

class UserPresentationRepository implements UserPresentationInterface
{
    use ResponseError;

    public function outPut(OutputBoundaryUserRegister $data): array
    {
        try {
                $data->id ?? throw new \RuntimeException();
            $publishEvents  = new PublishEvents();
            $userRegistered = new PublishUserRegistred();

            $publishEvents->addListener($userRegistered);
            $publishEvents->publish(
                new UserRegistredEvent(
                    $data->id,
                )
            );

            return [
                'data'    => [
                    'id'           => $data->id,
                    'id_user_auth' => $data->idUserAuth,
                    'id_account'   => $data->idAccount,
                    'id_address'   => $data->idAddress,
                    'id_profile'   => $data->idProfile,
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
