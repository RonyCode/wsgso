<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

final readonly class Token
{
    public function __construct(
        public ?int $id = null,
        public ?int $idUser = null,
        public ?string $token = null,
        public ?string $refreshToken = null,
        public ?int $dateCriation = null,
        public ?int $dateExpires = null,
        public ?int $excluded = null,
    ) {
    }
}
