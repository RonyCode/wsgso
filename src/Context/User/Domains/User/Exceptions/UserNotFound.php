<?php

namespace Gso\Ws\Context\User\Domains\User\Exceptions;

use Gso\Ws\Shared\ValuesObjects\Email;

class UserNotFound extends \DomainException
{
    public function __construct(Email $email)
    {
        parent::__construct("Usuário com email $email não encontrado");
    }
}
