<?php

namespace Gso\Ws\Domains\Event;


abstract class ListenerEvent
{
    public function process(Event $event): void
    {
        if ($this->canProcess($event)) {
            $this->reactTo($event);
        }
    }

    abstract public function canProcess(Event $event): bool;
    abstract public function reactTo(Event $event): void;
}
