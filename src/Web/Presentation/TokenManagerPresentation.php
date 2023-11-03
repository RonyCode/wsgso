<?php

namespace Gso\Ws\Web\Presentation;

use Gso\Ws\Context\User\App\UseCases\Token\GetTokenByCodUser\OutputBoundaryRefreshTokenCase;
use Gso\Ws\Context\User\Domains\User\Events\PublishTokenRefreshed;
use Gso\Ws\Context\User\Domains\User\Events\TokenRefreshedEvent;
use Gso\Ws\Context\User\Infra\User\Interface\TokenPresentationInterface;
use Gso\Ws\Shared\Event\PublishEvents;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

class TokenManagerPresentation implements TokenPresentationInterface
{
    use ResponseError;

    public function outPut(OutputBoundaryRefreshTokenCase $data): array
    {
        try {
            $publishEvents      = new PublishEvents();
            $publishRefreshTokenEvents = new PublishTokenRefreshed();

            $publishEvents->addListener($publishRefreshTokenEvents);
            $publishEvents->publish(
                new TokenRefreshedEvent(
                    $data->dateExpires,
                    $data->idUser
                )
            );


            return
                [
                    'cod_token' => $data->id,
                    'cod_usuario' => $data->idUser,
                    'token' => $data->token,
                    'refresh_token' => $data->refreshToken,
                    'data_criacao_token' => $data->dateCriation,
                    'data_expirar_token' => $data->dateExpires,
                    'excluido' => $data->excluido,
                ];
        } catch (RuntimeException) {
            $this->responseCatchError('erro ao retornar dados na camada:' . json_encode($this, JSON_THROW_ON_ERROR));
        }
    }
}
