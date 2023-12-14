<?php

namespace Gso\Ws\Context\User\Domains\User\Events\publish;

use Gso\Ws\Context\User\Domains\User\Events\command\UserSendedEmailSignUp;
use Gso\Ws\Shared\Event\interface\EventInterface;
use Gso\Ws\Shared\Event\ListenerEvent;
use Gso\Ws\Web\Message\Builder;

class PublishEmailSendedSignUpUserAuth extends ListenerEvent
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
        Builder::queue('email_sended', $server)->emit(
            [
                "message" =>
                    'Email ' . $event->emailUser() . ' enviado na data ' . $event->moment()->format(
                        'd/m/Y H:i:s'
                    ) . ' verifique sua caixa de entrada',
                "email"   => (string)$event->emailUser(),
            ],
        );
    }

    public function canProcess(EventInterface $event): bool
    {
        return $event instanceof UserSendedEmailSignUp;
    }
}
