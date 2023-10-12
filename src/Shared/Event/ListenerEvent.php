<?php

namespace Gso\Ws\Shared\Event;

use Gso\Ws\Shared\Event\interface\EventInterface;

abstract class ListenerEvent
{
    public function process(EventInterface $event): void
    {
        if ($this->canProcess($event)) {
            $this->reactTo($event);
        }
    }

    abstract public function canProcess(EventInterface $event): bool;
    abstract public function reactTo(EventInterface $event): void;
}
