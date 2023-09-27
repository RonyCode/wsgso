<?php

declare(strict_types=1);

namespace Gso\Ws\Domains\User;

final readonly class TokenUser
{
    public function __construct(
        public ?int $codToken = null,
        public ?int $codUsuario = null,
        public ?string $token = null,
        public ?string $refreshToken = null,
        public ?int $dataCriacao = null,
        public ?int $dataExpirar = null,
        public ?int $excluido = null,
    ) {
    }
}
