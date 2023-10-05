<?php

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Context\User\Infra\User\Repository\UserAuthRepositoryMemory;
use Gso\Ws\Context\User\Infra\User\Repository\UserRepository;
use Gso\Ws\Context\User\Infra\User\Repository\UserRepositoryMemory;
use Gso\Ws\Shared\ValuesObjects\DateMysqlToFormatBr;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Shared\ValuesObjects\Pass;
use JsonException;

final readonly class UserAuth implements UserAuthRepositoryInterface
{
    public function __construct(
        public ?int $id = null,
        public ?Email $email = null,
        public ?Pass $password = null,
        public ?Pass $passwordExternal = null,
        public ?DateMysqlToFormatBr $dataCriation = null,
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
        ?string $passExternal = null,
        ?string $dateCriation = null,
        ?int $excluded = null,
    ): self {
        return new UserAuth(
            $id ?? null,
            new Email($email) ?? null,
            new Pass($pass) ?? null,
            new Pass($passExternal) ?? null,
            new DateMysqlToFormatBr($dateCriation) ?? null,
            $excluded ?? null,
        );
    }


//    public function userAuthSignIn($email, $password): self
//    {
//        return $this->signIn($email, $password);
//    }

    public function signIn(?string $email, ?string $password): self
    {
        return (new UserAuthRepositoryMemory())->signIn($email, $password);
    }
}
