<?php

namespace Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp;

class InputBoundaryUserAuthSignUp
{
    public function __construct(
        public string $email,
    ) {
    }
}
