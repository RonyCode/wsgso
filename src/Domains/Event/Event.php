<?php

namespace Gso\Ws\Domains\Event;

interface Event extends \JsonSerializable
{

    public function moment(): \DateTimeImmutable;

}
