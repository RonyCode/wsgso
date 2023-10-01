<?php

namespace Gso\Ws\Context\User\Domains\User\Exceptions;

class TokenNotFound extends \DomainException
{
    public function __construct(string $nameUser)
    {
        parent::__construct("Token usuário " . $nameUser . " não encontrado");
    }
}
