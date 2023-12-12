<?php

namespace Gso\Ws\Context\User\App\UseCases\User\Register;

class OutPutBoundaryUserRegister
{
    public function __construct(
        public string $name,
        public string $cpf,
        public string $address,
        public string $number,
        public string $zipCOde,
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
