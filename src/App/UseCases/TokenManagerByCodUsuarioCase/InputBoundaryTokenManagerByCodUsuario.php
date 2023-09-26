<?php

namespace Gso\Ws\App\UseCases\TokenManagerByCodUsuarioCase;

readonly class InputBoundaryTokenManagerByCodUsuario
{
    public function __construct(public string $token)
    {
    }
}
