<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User\Events\command;

use Gso\Ws\Shared\Event\interface\EventInterface;

class UserRegistredEvent implements EventInterface
{
    private \DateTimeImmutable $moment;

    public function __construct(readonly public int $idUser)
    {
        $this->moment = new \DateTimeImmutable();
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
        $data->id = $this->idUser();
    }
}
