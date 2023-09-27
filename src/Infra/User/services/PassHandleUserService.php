<?php

namespace Gso\Ws\Infra\User\services;

use Gso\Ws\Domains\User\Interface\PassHandleUserInterface;
use Gso\Ws\Domains\ValuesObjects\Senha;
use http\Exception\InvalidArgumentException;

class PassHandleUserService implements PassHandleUserInterface
{

    /**
     * @throws \JsonException
     */
    public function encodePassUser(string $pass): string
    {
        if (empty($pass)) {
            throw new InvalidArgumentException();
        }
        $passFiltred = new Senha($pass);

        return password_hash($passFiltred, PASSWORD_ARGON2ID);
    }

    /**
     * @throws \JsonException
     */
    public function verifyPassUser(string $passText, string $passEncripted): bool
    {
        if (empty($passText) || empty($passEncripted)) {
            throw  new InvalidArgumentException();
        }
        $passFiltred = new Senha($passText);

        return password_verify($passFiltred, $passEncripted);
    }
}
