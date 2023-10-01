<?php

namespace Gso\Ws\Context\User\Domains\User\Events;

use Gso\Ws\Shared\Event\Event;
use Gso\Ws\Shared\ValuesObjects\Email;

class UserSignIn implements Event
{
    private \DateTimeImmutable $moment;

    public function __construct(readonly public Email $email)
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

    public function jsonSerialize(): mixed
    {
        return get_object_vars($this);
    }
}
