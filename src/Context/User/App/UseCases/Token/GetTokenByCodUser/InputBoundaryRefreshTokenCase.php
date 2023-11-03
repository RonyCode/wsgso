<?php

namespace Gso\Ws\Context\User\App\UseCases\Token\GetTokenByCodUser;

readonly class InputBoundaryRefreshTokenCase
{
    public function __construct(public string $token)
    {
    }
}
