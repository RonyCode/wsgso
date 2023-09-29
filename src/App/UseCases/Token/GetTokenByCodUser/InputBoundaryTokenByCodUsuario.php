<?php

namespace Gso\Ws\App\UseCases\TokenUser\GetTokenByCodUser;

readonly class InputBoundaryTokenByCodUsuario
{
    public function __construct(public string $token)
    {
    }
}
