<?php

namespace Gso\Ws\Context\User\Infra\User\Repository;

use Exception;
use Gso\Ws\Context\User\Domains\User\Exceptions\UserNotFound;
use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\UserAuth;
use Gso\Ws\Shared\ValuesObjects\Email;

class UserAuthRepositoryMemory implements UserAuthRepositoryInterface
{
    private array $usersAuth = [];

    /**@return UserAuth[] */
    public function adicionar(UserAuth $userAuth): array
    {
        $this->usersAuth[] = $userAuth;

        return $this->usersAuth;
//        return $this->usersAuth = array_unique($this->usersAuth);
    }

    /**
     * @throws Exception
     */
    public function buscaPorEmail(Email $email): UserAuth
    {
        $userFiltered = array_filter(
            $this->usersAuth,
            static fn(UserAuth $user) => $user->email === $email
        );

        if (count($userFiltered) === 0) {
            throw new UserNotFound($email);
        }

        if (count($userFiltered) > 1) {
            throw new \RuntimeException();
        }

        return $userFiltered[0];
    }

    public function buscarTodos(): array
    {
        return $this->usersAuth;
    }

    /**
     * @param int $codUsuario
     *
     * @return UserAuth
     */

    public function getUserAuthById(int $codUsuario): UserAuth
    {
        $userFiltered = array_filter(
            $this->usersAuth,
            static fn(UserAuth $user) => $user->codUsuario === $codUsuario
        );

        if (count($userFiltered) === 0) {
            throw new UserNotFound(new Email('teste@example.com'));
        }

        if (count($userFiltered) > 1) {
            throw new \RuntimeException();
        }

        return $userFiltered[0];
    }

    public function signIn(string $email, string $password): UserAuth
    {
        $this->adicionar(
            UserAuth::userAuthSerialize(
                1,
                'teste@gmail.com',
                '1234567teste',
                null,
                '2023-01-01',
                0,
            )
        );
        $this->adicionar(
            UserAuth::userAuthSerialize(
                2,
                'denis@gmail.com',
                '1234567d',
                null,
                '2023-01-01',
                0,
            )
        );
        $this->adicionar(
            UserAuth::userAuthSerialize(
                3,
                'ronyanderson@gmail.com',
                '1234567a',
                null,
                '2023-01-01',
                0,
            )
        );
        $userAuthFiltered = array_filter(
            $this->usersAuth,
            static fn(UserAuth $userAuth) => $userAuth->email == $email && $userAuth->password == $password
        );


        return array_values($userAuthFiltered)[0];
    }

    public function getUserAuthByEmail(string $email): UserAuth
    {
        $userFiltered = array_filter(
            $this->usersAuth,
            static fn(UserAuth $user) => $user->email === $email
        );

        if (count($userFiltered) === 0) {
            throw new UserNotFound(new Email('teste@example.com'));
        }

        if (count($userFiltered) > 1) {
            throw new \RuntimeException();
        }

        return $userFiltered[0];
    }
}
