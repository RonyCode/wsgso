<?php

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Shared\ValuesObjects\Cpf;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Shared\ValuesObjects\Phone;
use JsonException;

readonly class Account
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $cpf = null,
        public ?string $phone = null,
        public ?string $image = null,
        public ?int $excluded = null,
    ) {
        clone $this;
    }

    /**
     * @throws JsonException
     */
    public function __clone(): void
    {
        $this->email = new Email($this->email);
        $this->cpf   = new Cpf($this->cpf);
        $this->phone = new Phone($this->phone);
    }

    public function sanitize(): Account|static
    {
        return clone $this;
    }
}
