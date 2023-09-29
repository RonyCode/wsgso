<?php

namespace Gso\Ws\Domains\User\Interface;

use Gso\Ws\Domains\User\Token;

interface TokenUserRepositoryInterface
{
    public function selectTokenByCodUsuario(int $codusuario): Token;

    public function saveTokenUsuario(Token $tokenManagerModel): Token;
}
