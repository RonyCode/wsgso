<?php

namespace Gso\Ws\Context\User\App\UseCases\User\SignInUser;

readonly class InputBoundaryUserSignIn
{
    public function __construct(
        public string $email,
        public string $password,
        public ?int $isUserExternal = null,
        public ?string $dateCriation = null,
        public ?int $excluded = null
    ) {
    }
}
