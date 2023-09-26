<?php

namespace Gso\App\Infra\Repositories\RepositoriesPresentation;

use Gso\Ws\App\Helper\ResponseError;
use Gso\App\App\UseCases\TokenManagerByCodUsuarioCase\OutputBoundaryTokenManagerByCodUsuario;
use Gso\App\Infra\Interfaces\InterfacesPresentation\TokenManagerByCodUsuarioPresentationInterface;
use RuntimeException;

class TokenManagerPresentation implements TokenManagerByCodUsuarioPresentationInterface
{
    use ResponseError;

    public function outPut(OutputBoundaryTokenManagerByCodUsuario $data): array
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
