<?php

namespace Gso\Ws\Context\User\App\UseCases\User\Address\AddressStatesGetAll;

class OutputBoundaryAddressStatesGetAll
{
    public function __construct(
        public ?int $id = null,
        public ?string $state = null,
        public ?string $shortName = null,
    ) {
    }
}
