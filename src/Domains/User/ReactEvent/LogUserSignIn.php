<?php

namespace Gso\Ws\Domains\User\ReactEvent;

use Gso\Ws\App\UseCases\UserAuthCase\SignInUser\UserSignIn;
use Gso\Ws\Domains\ListennerEvent;
use Gso\Ws\Domains\User\Interface\Event;

class LogUserSignIn extends ListennerEvent
{
    /**
     * @param Event $event
     */
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
