<?php

namespace Gso\Ws\Infra\User\Repository;

use Exception;
use Gso\Ws\Domains\User\Exceptions\UserNotFound;
use Gso\Ws\Domains\User\User;
use Gso\Ws\Domains\ValuesObjects\Email;

class UserRepositoryMemory
{
    private array $users = [];

    public function adicionar(User $user): void
    {
        $this->users[] = $user;
    }

    /**
     * @throws Exception
     */
    public function buscaPorEmail(Email $email): User
    {
        $userFiltered = array_filter(
            $this->users,
            static fn(User $user) => $user->email === $email
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
        return $this->users;
    }
}
