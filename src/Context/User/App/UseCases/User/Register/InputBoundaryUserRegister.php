<?php

namespace Gso\Ws\Context\User\App\UseCases\User\Register;

class InputBoundaryUserRegister
{
    public function __construct(
        public string $name,
        public string $cpf,
        public string $address,
        public string $complement,
        public string $shortNameState,
        public string $number,
        public string $zipCode,
        public string $city,
        public string $state,
        public string $district,
        public string $phone,
        public string $birthsDay,
        public string $email,
        public string $password,
    ) {
    }
}
