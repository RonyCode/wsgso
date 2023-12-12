<?php

namespace Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSave;

class InputBoundaryUserSave
{
    public function __construct(
        public string $email,
    ) {
    }
}
