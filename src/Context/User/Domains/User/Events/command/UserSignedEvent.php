<?php

namespace Gso\Ws\Context\User\Domains\User\Events\command;

use Gso\Ws\Shared\Event\interface\EventInterface;
use Gso\Ws\Shared\ValuesObjects\Email;

class UserSignedEvent implements EventInterface
{
    private \DateTimeImmutable $moment;

    public function __construct(readonly public Email $email, readonly public int $idUser)
    {
        $this->moment = new \DateTimeImmutable();
    }

    public function emailUser(): Email
    {
        return $this->email;
    }

    public function idUser(): int
    {
        return $this->idUser;
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
