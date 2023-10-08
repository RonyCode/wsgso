<?php

namespace Gso\Ws\Context\User\Infra\User\Repository;

use Gso\Ws\Context\User\App\UseCases\User\SignInUser\OutputBoundaryUserSignIn;
use Gso\Ws\Context\User\Infra\User\Interface\UserPresentationInterface;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

class UserPresentationRepository implements UserPresentationInterface
{
    use ResponseError;

    public function outPut(OutputBoundaryUserSignIn $data): array
    {
        try {
                $data->codUsuario ?? throw new \RuntimeException();

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
