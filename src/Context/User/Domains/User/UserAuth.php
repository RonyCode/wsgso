<?php

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Context\User\Infra\Connection\GlobalConnection;
use Gso\Ws\Context\User\Infra\User\Repository\UserAuthRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserAuthRepositoryMemory;
use Gso\Ws\Context\User\Infra\User\Repository\UserRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserRepositoryMemory;
use Gso\Ws\Shared\ValuesObjects\DateMysqlToFormatBr;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Shared\ValuesObjects\Pass;
use JsonException;

final readonly class UserAuth
{
    public function __construct(
        public ?int $id = null,
        public ?Email $email = null,
        public ?Pass $password = null,
        public ?int $isUserExternal = null,
        public ?DateMysqlToFormatBr $dateCriation = null,
        public ?int $excluded = null,
    ) {
    }

    /**
     * @throws JsonException
     */
    public static function userAuthSerialize(
        ?int $id = null,
        ?string $email = null,
        ?string $pass = null,
        ?int $isUserExternal = null,
        ?string $dateCriation = null,
        ?int $excluded = null,
    ): self {
        return new UserAuth(
            $id ?? null,
            new Email($email) ?? null,
            new Pass($pass) ?? null,
            $isUserExternal ?? null,
            new DateMysqlToFormatBr($dateCriation) ?? null,
            $excluded ?? null,
        );
    }


    public function signIn(?string $email, ?string $password): self
    {
        $conex = new GlobalConnection();

        return (new UserAuthRepository($conex))->signIn($email, $password);
    }

    public function getUserAuth(): self
    {
        return $this;
    }

    public function getUsuarioByEmail(string $email): UserAuth
    {
        return $this;
    }
}