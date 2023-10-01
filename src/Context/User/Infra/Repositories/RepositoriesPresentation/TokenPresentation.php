<?php

namespace Gso\Ws\Context\User\Infra\Repositories\RepositoriesPresentation;

use Gso\Ws\App\UseCases\TokenUserCase\GetTokenByCodUser\OutputBoundaryTokenByCodUsuario;
use Gso\Ws\Context\User\Infra\Interfaces\InterfacesPresentation\TokenByCodUsuarioPresentationInterface;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

class TokenPresentation implements TokenByCodUsuarioPresentationInterface
{
    use ResponseError;

    public function outPut(OutputBoundaryTokenByCodUsuario $data): array
    {
        try {
            return
                [
                    'cod_token' => $data->codToken,
                    'cod_usuario' => $data->codUsuario,
                    'token' => $data->token,
                    'refresh_token' => $data->refreshToken,
                    'data_criacao_token' => $data->dataCriacaoToken,
                    'data_expirar_token' => $data->dataExpirarToken,
                    'excluido' => $data->excluido,
                ];
        } catch (RuntimeException) {
            $this->responseCatchError('erro ao retornar dados na camada:'.json_encode($this));
        }
    }
}
