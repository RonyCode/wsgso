<?php

namespace Gso\Ws\Context\User\Domains\User\Events\Interface;

use Gso\Ws\Shared\ValuesObjects\Email;

interface UserSignInEventInterface
{
    public function emailUser(): Email;

    public function moment(): \DateTimeImmutable;
}
