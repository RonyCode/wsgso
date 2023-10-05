<?php

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Shared\ValuesObjects\DateMysqlToFormatBr;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Shared\ValuesObjects\Pass;
use JsonException;

final readonly class UserAuth
{
    public function __construct(
        public ?Email $email = null,
        public ?Pass $password = null,
        public ?Pass $confirmPassword = null,
        public ?DateMysqlToFormatBr $dataCadastro = null,
        public ?int $excluded = null,
    ) {
    }


    /**
     * @throws JsonException
     */
    public static function userAuthSerialize(
        ?string $email,
        ?string $pass,
        ?string $passExternal,
        ?string $dateCriation,
        ?int $excluded,
    ): self {
        return new UserAuth(
            new Email($email),
            new Pass($pass),
            new Pass($passExternal),
            new DateMysqlToFormatBr($dateCriation),
            $excluded,
        );
    }
}
