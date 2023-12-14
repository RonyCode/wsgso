<?php

namespace Gso\Ws\Context\User\Domains\User\Events\publish;

use Gso\Ws\Context\User\Domains\User\Events\command\UserRegistredEvent;
use Gso\Ws\Context\User\Domains\User\Events\command\UserSignedEvent;
use Gso\Ws\Shared\Event\interface\EventInterface;
use Gso\Ws\Shared\Event\ListenerEvent;
use Gso\Ws\Web\Message\Builder;

class PublishUserRegistred extends ListenerEvent
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
        Builder::queue('user_registered', $server)->emit(
            [
                "message" =>
                    'Usuário com ID ' . $event->idUser() . ' registrado na data ' . $event->moment()->format(
                        'd/m/Y H:i:s'
                    ),
                "id"      => $event->idUser(),
            ],
        );
    }

    public function canProcess(EventInterface $event): bool
    {
        return $event instanceof UserRegistredEvent;
    }
}
