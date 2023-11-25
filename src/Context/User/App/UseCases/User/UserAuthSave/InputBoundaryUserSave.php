<?php

namespace Gso\Ws\Context\User\App\UseCases\User\UserAuthSave;

class InputBoundaryUserSave
{
    public function __construct(
        public string $email,
    ) {
    }
}
