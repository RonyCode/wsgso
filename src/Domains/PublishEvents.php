<?php

namespace Gso\Ws\Domains;



use Gso\Ws\Domains\User\Events\UserSign;
use Gso\Ws\Domains\User\Interface\Event;

class PublishEvents
{
    private array $listerners = [];


    public function __construct(
        public readonly UserSign $eventoDominiUserSign,

    )
    {
    }

    public function addListener(ListennerEvent $listener): void
    {
        $this->listerners[] = $listener;
    }

    public function publish(Event $event): void
    {
        /** @var ListennerEvent $listerner */
        foreach ($this->listerners as $listerner) {
            $listerner->process($event);

        }
    }
}
