<?php

namespace Gso\Ws\Context\User\Domains\User\Events;

use Gso\Ws\Shared\Event\interface\EventInterface;
use Gso\Ws\Shared\ValuesObjects\Email;

class UserSendedEmailSignUp implements EventInterface
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

    public function jsonSerialize(): array
    {
        return get_object_vars($this);
    }

    public function eventData($data): void
    {
        $data->email = $this->emailUser();
    }
}
