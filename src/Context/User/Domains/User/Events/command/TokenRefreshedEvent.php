<?php

namespace Gso\Ws\Context\User\Domains\User\Events\command;

use Gso\Ws\Shared\Event\interface\EventInterface;

class TokenRefreshedEvent implements EventInterface
{
    private \DateTimeImmutable $moment;

    public function __construct(readonly public int $dataExpires, readonly public int $idUser)
    {
        $this->moment = new \DateTimeImmutable();
    }

    public function dataExpires(): string
    {
        return date('d/m/Y H:i:s', $this->dataExpires);
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
        $data->dataExpires = $this->dataExpires();
        $data->idUser = $this->idUser();
    }
}
