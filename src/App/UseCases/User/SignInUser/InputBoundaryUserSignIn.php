<?php

namespace Gso\Ws\App\UseCases\User\SignInUser;

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
