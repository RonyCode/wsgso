<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Shared\ValuesObjects\Cep;
use JsonException;

final readonly class Address
{
    public function __construct(
        public ?int $id = null,
        public ?string $address = null,
        public ?string $number = null,
        public ?Cep $zipCode = null,
        public ?string $complement = null,
        public ?string $district = null,
        public ?string $city = null,
        public ?string $state = null,
        public ?string $shortName = null,
        public ?int $excluded = null,
    ) {
    }

    /**
     * @throws JsonException
     */
    public static function addressSerialize(
        ?int $id = null,
        ?string $address = null,
        ?string $number = null,
        ?string $zipCode = null,
        ?string $complement = null,
        ?string $district = null,
        ?string $city = null,
        ?string $state = null,
        ?string $shortName = null,
        ?int $excluded = null
    ): self {
        return new Address(
            $id,
            $address,
            $number,
            new Cep($zipCode),
            $complement,
            $district,
            $city,
            $state,
            $shortName,
            $excluded
        );
    }
}
