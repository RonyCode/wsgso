<?php

namespace Gso\Ws\Domains\User\Events\Interface;

use Gso\Ws\Domains\ValuesObjects\Email;

interface UserSignInEventInterface
{
    public function emailUser(): Email;

    public function moment(): \DateTimeImmutable;
}
