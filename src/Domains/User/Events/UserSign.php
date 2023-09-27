<?php

namespace Gso\Ws\Domains\User\Events;

use Gso\Ws\Domains\User\Interface\Event;
use Gso\Ws\Domains\ValuesObjects\Email;

class UserSign implements Event
{
    private \DateTimeImmutable $moment;

    public function __construct( readonly public Email $email )
    {
        $this->moment = new \DateTimeImmutable();
    }

    public function emailUser(): Email
    {
        return $this->email;
    }

    public function moment(): \DateTimeImmutable
    {
        return $this->moment;
    }
}
