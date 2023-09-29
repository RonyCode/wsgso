<?php

namespace Gso\Ws\Domains\Event;


use Gso\Ws\Domains\User\Events\UserSignIn;

class PublishEvents
{
    private array $listeners = [];

//    public function __construct(
//        public readonly UserSignIn $eventoDominiUserSign,
//
//    ) {
//    }

    public function addListener(ListenerEvent $listener): void
    {
        $this->listeners[] = $listener;
    }

    public function publish(Event $event): void
    {
        /** @var ListenerEvent $listener */
        foreach ($this->listeners as $listener) {
            $listener->process($event);
        }
    }
}
