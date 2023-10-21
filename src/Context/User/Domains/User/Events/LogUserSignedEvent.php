<?php

namespace Gso\Ws\Context\User\Domains\User\Events;

use Gso\Ws\Shared\Event\interface\EventInterface;
use Gso\Ws\Shared\Event\ListenerEvent;
use Gso\Ws\Web\Message\Builder;

class LogUserSignedEvent extends ListenerEvent
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

        Builder::queue('queue', $server)->emit(
            [
                "info"  =>
                    'Usuário com email ' . $event->emailUser() . ' logado na data ' . $event->moment()->format(
                        'd/m/Y'
                    ),
                "teste" => true,
                "sa"    => false,
                "quit"  => true
            ],
        );
    }

    public function canProcess(EventInterface $event): bool
    {
        return $event instanceof UserSignedEvent;
    }
}
