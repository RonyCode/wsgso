<?php

namespace Gso\Ws\Context\User\Domains\User\Interface;

use Gso\Ws\Context\User\Domains\User\Token;

interface TokenUserRepositoryInterface
{
    public function selectTokenByCodUsuario(int $idUser): Token;

    public function saveTokenUsuario(Token $tokenManagerModel): Token;
}
