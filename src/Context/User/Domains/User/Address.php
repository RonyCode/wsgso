<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Shared\ValuesObjects\Cep;

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
    ) {
    }
}
