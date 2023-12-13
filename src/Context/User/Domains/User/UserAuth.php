<?php

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Context\User\Domains\User\Interface\UserAuthRepositoryInterface;
use Gso\Ws\Shared\ValuesObjects\DateMysqlToFormatBr;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Shared\ValuesObjects\Pass;
use JsonException;

final readonly class UserAuth
{
    private UserAuthRepositoryInterface $authRepository;


    public function __construct(
        public ?int $id = null,
        public ?string $email = null,
        public ?string $password = null,
        public ?int $isUserExternal = null,
        public ?string $dateCriation = null,
        public ?int $excluded = null,
    ) {
    }


    /**
     * @throws JsonException
     */
    public function __clone()
    {
        $this->email        = new Email($this->email);
        $this->password     = new Pass($this->password);
        $this->dateCriation = new DateMysqlToFormatBr($this->dateCriation);
    }

    public function serializeUserAuth(): UserAuth|static
    {
        return clone $this;
    }
}
