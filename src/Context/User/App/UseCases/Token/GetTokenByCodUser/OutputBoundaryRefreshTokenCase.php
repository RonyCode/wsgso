<?php

namespace Gso\Ws\Context\User\App\UseCases\Token\GetTokenByCodUser;

readonly class OutputBoundaryRefreshTokenCase
{
    public function __construct(
        public ?int $id = null,
        public ?int $idUser = null,
        public ?string $token = null,
        public ?string $refreshToken = null,
        public ?int $dateCriation = null,
        public ?int $dateExpires = null,
        public ?int $excluido = null,
    ) {
    }
}
