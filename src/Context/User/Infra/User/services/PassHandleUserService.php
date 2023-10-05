<?php

namespace Gso\Ws\Context\User\Infra\User\services;

use Gso\Ws\Context\User\Domains\User\Interface\PassHandleUserInterface;
use Gso\Ws\Shared\ValuesObjects\Pass;
use http\Exception\InvalidArgumentException;

class PassHandleUserService implements PassHandleUserInterface
{

    /**
     * @throws \JsonException
     */
    public  function encodePassUser(string $pass): string
    {
        if (empty($pass)) {
            throw new InvalidArgumentException();
        }
        $passFiltred = new Pass($pass);

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
        $passFiltred = new Pass($passText);

        return password_verify($passFiltred, $passEncripted);
    }
}
