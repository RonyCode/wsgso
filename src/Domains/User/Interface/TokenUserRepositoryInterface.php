<?php

namespace Gso\Ws\Domains\User\Interface;

use Gso\Ws\Domains\User\TokenUser;

interface TokenUserRepositoryInterface
{
    public function selectTokenByCodUsuario(int $codusuario): TokenUser;

    public function saveTokenUsuario(TokenUser $tokenManagerModel): TokenUser;
}
