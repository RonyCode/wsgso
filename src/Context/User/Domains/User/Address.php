<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Shared\ValuesObjects\Cep;
use Gso\Ws\Shared\ValuesObjects\CepTest;
use Gso\Ws\Web\Helper\ValidateParams;

class Address
{
    /**
     * @throws \JsonException
     */
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
        clone $this;
    }

    /**
     * @throws \JsonException
     */
    public function __clone(): void
    {
        $this->zipCode = (string)(new Cep($this->zipCode));
    }


    public function sanitize()
    {
        return clone $this;
    }
}
