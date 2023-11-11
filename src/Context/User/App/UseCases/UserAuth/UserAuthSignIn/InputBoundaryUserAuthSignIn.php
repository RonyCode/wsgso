<?php

namespace Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignIn;

final readonly class InputBoundaryUserAuthSignIn
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
