<?php

namespace Gso\Ws\Context\User\Domains\User\Events;

use Gso\Ws\Shared\Event\interface\EventInterface;
use Gso\Ws\Shared\Event\ListenerEvent;

class LogUserSignedEvent extends ListenerEvent
{
    public function reactTo(EventInterface $event): void
    {
//        fprintf(
//            STDERR,
//            'Usuario com email %s logado da data %s',
//            $event->emailUser(),
//            $event->moment()->format('d/m/Y'),
//        );
        echo 'Usuário com email ' . $event->emailUser() . ' logado na data ' . $event->moment()->format('d/m/Y');
    }

    public function canProcess(EventInterface $event): bool
    {
        return $event instanceof UserSignedEvent;
    }
}
