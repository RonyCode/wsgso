<?php

namespace Gso\Ws\Context\User\Domains\User\Events\publish;

use Gso\Ws\Context\User\Domains\User\Events\command\TokenRefreshedEvent;
use Gso\Ws\Shared\Event\interface\EventInterface;
use Gso\Ws\Shared\Event\ListenerEvent;
use Gso\Ws\Web\Message\Builder;

class PublishTokenRefreshed extends ListenerEvent
{
    /**
     * @throws \JsonException
     */
    public function reactTo(EventInterface $event): void
    {
        $server = [
            'host' => 'localhost',
            'port' => 5672,
            'user' => 'guest',
            'pass' => 'guest',
        ];

        //Evento com mensagem registrada no RABBITMQ
        Builder::queue('refresh_token', $server)->emit(
            [
                "id_user" => $event->idUser(),
                "info"    =>
                    'Token foi reativado com sucesso! tempo de validade:' .
                    $event->dataExpires() . ' requisição feita em: ' .
                    $event->moment()->format('d/m/Y H:i:s'),
            ],
        );
    }

    public function canProcess(EventInterface $event): bool
    {
        return $event instanceof TokenRefreshedEvent;
    }
}
