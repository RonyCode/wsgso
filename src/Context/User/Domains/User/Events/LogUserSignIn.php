<?php

namespace Gso\Ws\Context\User\Domains\User\Events;

use Gso\Ws\Shared\Event\Event;
use Gso\Ws\Shared\Event\ListenerEvent;

class LogUserSignIn extends ListenerEvent
{
    /**@param UserSignIn $event */
    public function reactTo(Event $event): void
    {
        fprintf(
            STDERR,
            'Usuario com email %s logado da data %s',
            $event->emailUser(),
            $event->moment()->format('d/m/Y'),
        );
    }

    public function canProcess(Event $event): bool
    {
        return $event instanceof UserSignIn;
    }
}
