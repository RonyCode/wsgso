<?php

declare(strict_types=1);

namespace Gso\Ws\Domains\Models;

final class TokenManagerModel
{
    public function __construct(
        public readonly ?int $codToken = null,
        public readonly ?int $codUsuario = null,
        public readonly ?string $token = null,
        public readonly ?string $refreshToken = null,
        public readonly ?int $dataCriacao = null,
        public readonly ?int $dataExpirar = null,
        public readonly ?int $excluido = null,
    ) {
    }
}
