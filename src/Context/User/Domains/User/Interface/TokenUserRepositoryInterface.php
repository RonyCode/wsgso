<?php

namespace Gso\Ws\Context\User\Domains\User\Interface;

use Gso\Ws\Context\User\Domains\User\Token;

interface TokenUserRepositoryInterface
{
    public function selectTokenByCodUsuario(int $codusuario): Token;

    public function saveTokenUsuario(Token $tokenManagerModel): Token;
}
