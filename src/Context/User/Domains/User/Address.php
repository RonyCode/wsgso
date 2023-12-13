<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Shared\ValuesObjects\Cep;

final readonly class Address
{
    public function __construct(
        public ?int $id = null,
        public ?string $address = null,
        public ?string $number = null,
        public ?string $zipCode = null,
        public ?string $complement = null,
        public ?string $district = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $shortName = null,
        public ?int $excluded = null,
    ) {
    }

    /**
     * @throws \JsonException
     */
    public function __clone(): void
    {
        $this->zipCode = (string)new Cep($this->zipCode);
    }
    public function serializeAddress(): Address|static
    {
        return clone $this;
    }
}
