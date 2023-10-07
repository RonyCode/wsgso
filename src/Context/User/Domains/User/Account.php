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
        readonly public ?int $userAuthid = null,
        readonly public ?string $nome = null,
        readonly public ?Email $email = null,
        readonly public ?Cpf $cpf = null,
        readonly public ?Phone $phone = null,
        readonly public ?string $image = null,
        readonly public ?int $excluded = null,
        private ?Address $address = null,
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
        ?int $userAuthid = null,
        ?string $nome = null,
        ?string $email = null,
        ?string $cpf = null,
        ?string $phone = null,
        ?string $image = null,
        ?int $excluded = null
    ): self {
        return new Account(
            $id,
            $userAuthid,
            $nome,
            new Email($email),
            new Cpf($cpf),
            new Phone($phone),
            $image,
            $excluded,
            null
        );
    }

    /**
     * @throws JsonException
     */
    public function addAddress(
        string $logradouro,
        string $numero,
        string $cep,
        string $complemento,
        string $bairro,
        string $cidade,
        string $estado,
        int $excluded
    ): self {
        $this->address = Address::addressSerialize(
            $logradouro,
            $numero,
            new Cep($cep),
            $complemento,
            $bairro,
            $cidade,
            $estado,
            $excluded
        );

        return $this;
    }
}
