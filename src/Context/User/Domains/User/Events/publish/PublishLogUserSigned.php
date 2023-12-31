<?php

namespace Gso\Ws\Context\User\Domains\User\Events\publish;

use Gso\Ws\Context\User\Domains\User\Events\command\UserSignedEvent;
use Gso\Ws\Shared\Event\interface\EventInterface;
use Gso\Ws\Shared\Event\ListenerEvent;
use Gso\Ws\Web\Message\Builder;

class PublishLogUserSigned extends ListenerEvent
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
        Builder::queue('auth', $server)->emit(
            [
                "message" =>
                    'Usuário com email ' . $event->emailUser() . ' logado na data ' . $event->moment()->format(
                        'd/m/Y H:i:s'
                    ),
                "email"   => (string)$event->emailUser(),
                "id"      => $event->idUser(),
            ],
        );
    }

    public function canProcess(EventInterface $event): bool
    {
        return $event instanceof UserSignedEvent;
    }
}
