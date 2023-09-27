<?php

namespace Gso\Ws\Infra\User\Repository;

use Gso\Ws\App\UseCases\User\SignInUser\OutputBoundaryUserSignIn;
use Gso\Ws\Infra\User\Interface\UserPresentationInterface;
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
                'data' => [
                    'cod_usuario' => (string) $data->codUsuario,
                    'nome' => (string) $data->nome,
                    'email' => (string) $data->email,
                    'image' => (string) $data->image,
                    'token' => (string) $data->token,
                    'refresh_token' => $data->refreshToken,
                    'data_criacao_token' => $data->dataCriacaoToken,
                    'data_expirar_token' => $data->dataExpirarToken,
                ],
                'code' => 202,
                'status' => 'success',
                'message' => 'Login realizado com sucesso',
            ];
        } catch (RuntimeException) {
            return [
                'code' => 404,
                'status' => 'failure',
                'message' => $this->responseCatchError('erro OutputBoundary'),
            ];
        }
    }
}
