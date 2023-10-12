<?php

namespace Gso\Ws\Shared\Event\interface;

interface EventInterface extends \JsonSerializable
{
    public function moment(): \DateTimeImmutable;

    public function eventData($data);
}
