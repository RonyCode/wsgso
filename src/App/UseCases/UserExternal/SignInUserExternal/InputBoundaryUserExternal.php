<?php

namespace Gso\Ws\App\UseCases\UserExternal\SignInUserExternal;

readonly class InputBoundaryUserExternal
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
