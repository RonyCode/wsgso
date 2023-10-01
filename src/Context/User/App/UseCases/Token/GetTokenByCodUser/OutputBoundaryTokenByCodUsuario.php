<?php

namespace Gso\Ws\Context\User\App\UseCases\Token\GetTokenByCodUser;

readonly class OutputBoundaryTokenByCodUsuario
{
    public function __construct(
        public ?int $codToken = null,
        public ?int $codUsuario = null,
        public ?string $token = null,
        public ?string $refreshToken = null,
        public ?int $dataCriacaoToken = null,
        public ?int $dataExpirarToken = null,
        public ?int $excluido = null,
    ) {
    }
}
