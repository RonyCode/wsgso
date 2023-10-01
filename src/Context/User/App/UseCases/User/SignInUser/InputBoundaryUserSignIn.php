<?php

namespace Gso\Ws\Context\User\App\UseCases\User\SignInUser;

readonly class InputBoundaryUserSignIn
{
    public function __construct(
        public string $email,
        public string $senha,
        public ?string $nome = null,
        public ?string $image = null,
        public ?int $isUserExterno = null
    ) {
    }
}
