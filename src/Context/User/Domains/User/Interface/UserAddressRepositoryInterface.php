<?php

namespace Gso\Ws\Context\User\Domains\User\Interface;

use Gso\Ws\Context\User\Domains\User\Address;

interface UserAddressRepositoryInterface
{
    public function getAllStates(): array;

    public function getAllCities(): array;

    public function getAllCitiesByState(string $state): array;

    public function saveNewAddressUser(Address $address): Address;
}
