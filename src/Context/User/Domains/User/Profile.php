<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Shared\ValuesObjects\Cpf;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Shared\ValuesObjects\Phone;

final readonly class Profile
{
    public function __construct(
        public ?Cpf $cpf = null,
        public ?string $name = null,
        public ?Email $email = null,
        public ?Phone $phone = null,
        public ?Address $address = null,
        public ?string $image = null
    ) {
    }
}
