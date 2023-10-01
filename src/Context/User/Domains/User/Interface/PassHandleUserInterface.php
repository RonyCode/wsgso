<?php

namespace Gso\Ws\Context\User\Domains\User\Interface;

interface PassHandleUserInterface
{
    public function encodePassUser(string $pass): string;

    public function verifyPassUser(string $passText, string $passEncripted): bool;
}
