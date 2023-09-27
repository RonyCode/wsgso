<?php

namespace Gso\Ws\Domains\User\Interface;

use Gso\Ws\Domains\ValuesObjects\Senha;

interface PassHandleUserInterface
{
    public function encodePassUser(string $pass): string;

    public function verifyPassUser(string $passText, string $passEncripted): bool;
}
