<?php

namespace Gso\Ws\Context\User\Infra\User\Repository;

use Exception;
use Gso\Ws\Context\User\Domains\User\Exceptions\UserNotFound;
use Gso\Ws\Context\User\Domains\User\Interface\UserRepositoryInterface;
use Gso\Ws\Context\User\Domains\User\Token;
use Gso\Ws\Context\User\Domains\User\User;
use Gso\Ws\Context\User\Infra\Repositories\RepositoriesModel\TokenUserMemoryRepository;
use Gso\Ws\Shared\ValuesObjects\Email;

class UserRepositoryMemory implements UserRepositoryInterface
{
    private array $users = [];

    /**@return User[] */
    public function adicionar(User $user): array
    {
        $this->users[] = $user;

        return $this->users = array_unique($this->users);
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

    /**
     * @param string $email
     * @param string $senha
     *
     * @return User
     * @throws \JsonException
     */
    public function login(string $email, string $senha): User
    {
        $this->adicionar(
            User::userSerialize(
                123,
                '01680562169',
                'ronyanderson',
                'ronyanderson@gmail.com',
                '1234567a',
                null,
                '2020-01-01',
                '',
                0
            )
        );
        (new TokenUserMemoryRepository())->saveTokenUsuario(
            new Token(
                null,
                123
            )
        );
        $userFiltered = array_filter(
            $this->users,
            static fn(User $user) => $user->email == $email && $user->senha == $senha
        );

        return $userFiltered[0];
    }

    public function getUsuarioById(int $codUsuario): User
    {
        $userFiltered = array_filter(
            $this->users,
            static fn(User $user) => $user->codUsuario === $codUsuario
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
