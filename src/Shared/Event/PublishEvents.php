<?php

namespace Gso\Ws\Shared\Event;

use Gso\Ws\Shared\Event\interface\EventInterface;

class PublishEvents
{
    private array $listeners = [];

    public function addListener(ListenerEvent $listener): void
    {
        $this->listeners[] = $listener;
    }

    public function publish(EventInterface $event): void
    {
        /** @var ListenerEvent $listener */
        foreach ($this->listeners as $listener) {
            $listener->process($event);
        }
    }
}
