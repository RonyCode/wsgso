<?php

namespace Gso\Ws\Shared\Event;

interface Event extends \JsonSerializable
{
    public function moment(): \DateTimeImmutable;
}
