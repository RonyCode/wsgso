<?php

namespace Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignUp;

use Gso\Ws\Shared\ValuesObjects\Email;

class OutputBoundaryUserAuthSignUp
{
    public function __construct(
        public ?Email $email = null,
        public ?string $token = null,
    ) {
    }
}
