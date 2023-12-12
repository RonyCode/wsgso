<?php

namespace Gso\Ws\Context\User\App\UseCases\User\Address\AddressCitiesByState;

class OutPutBoundaryAddressCitiesByState
{
    public function __construct(
        public ?int $id = null,
        public ?string $city = null,
        public ?string $state = null,
    ) {
    }
}
