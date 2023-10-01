<?php

namespace Gso\Ws\Shared\Event;

class PublishEvents
{
    private array $listeners = [];

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
