<?php

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Shared\ValuesObjects\Cep;
use Gso\Ws\Shared\ValuesObjects\Cpf;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Shared\ValuesObjects\Phone;
use JsonException;

class Account
{
    public function __construct(
        readonly public ?int $id = null,
        readonly public ?string $name = null,
        readonly public ?Email $email = null,
        readonly public ?Cpf $cpf = null,
        readonly public ?Phone $phone = null,
        readonly public ?string $image = null,
        readonly public ?int $excluded = null,
    ) {
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    /**
     * @throws JsonException
     */
    public static function accountSerialize(
        ?int $id = null,
        ?string $name = null,
        ?string $email = null,
        ?string $cpf = null,
        ?string $phone = null,
        ?string $image = null,
        ?int $excluded = null,
    ): self {

        return new Account(
            $id ?? null,
            $name ?? null,
            new Email($email) ?? null,
            new Cpf($cpf) ?? null,
            new Phone($phone) ?? null,
            $image ?? null,
            $excluded ?? null,
        );
    }
}
