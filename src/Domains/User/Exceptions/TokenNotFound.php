<?php

namespace Gso\Ws\Domains\User\Exceptions;

class TokenNotFound extends \DomainException
{
    public function __construct(string $nameUser)
    {
        parent::__construct("Token usuário " . $nameUser . " não encontrado");
    }
}
