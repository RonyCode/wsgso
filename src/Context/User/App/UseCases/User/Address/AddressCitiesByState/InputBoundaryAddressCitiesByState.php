<?php

namespace Gso\Ws\Context\User\App\UseCases\User\Address\AddressCitiesByState;

class InputBoundaryAddressCitiesByState
{
    public function __construct(
        public string $state,
    ) {
    }
}
