<?php

namespace Gso\Ws\Context\User\App\UseCases\Token\GetTokenByCodUser;

readonly class InputBoundaryTokenByCodUsuario
{
    public function __construct(public string $token)
    {
    }
}
