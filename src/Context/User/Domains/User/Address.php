<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Shared\ValuesObjects\Cep;
use JsonException;

final readonly class Address
{
    public function __construct(
        public ?string $logradouro = null,
        public ?string $numero = null,
        public ?Cep $cep = null,
        public ?string $complemento = null,
        public ?string $bairro = null,
        public ?string $cidade = null,
        public ?string $estado = null,
        public ?int $excluded = null,
    ) {
    }

    /**
     * @throws JsonException
     */
    public static function addressSerialize(
        ?string $logradouro,
        ?string $numero,
        ?string $cep,
        ?string $complemento,
        ?string $bairro,
        ?string $cidade,
        ?string $estado,
        ?int $excluded
    ): self {
        return new Address(
            $logradouro,
            $numero,
            new Cep($cep),
            $complemento,
            $bairro,
            $cidade,
            $estado,
            $excluded
        );
    }
}
