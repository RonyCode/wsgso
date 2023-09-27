<?php

namespace Gso\Ws\Domains\User\Exceptions;

use Gso\Ws\Domains\ValuesObjects\Email;

class UserNotFound extends \DomainException
{
    public function __construct(Email $email)
    {
        parent::__construct("Usuário com email $email não encontrado");
    }
}
