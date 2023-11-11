<?php

namespace Gso\Ws\Context\User\App\UseCases\UserAuth\UserAuthSignIn;

use Gso\Ws\Shared\ValuesObjects\DateMysqlToFormatBr;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Shared\ValuesObjects\Pass;

final readonly class OutputBoundaryUserAuthSignIn
{
    public function __construct(
        public ?int $codUsuario = null,
        public ?Email $email = null,
        public ?Pass $senha = null,
        public ?DateMysqlToFormatBr $dataCadastro = null,
        public ?string $token = null,
        public ?string $refreshToken = null,
        public ?int $dataCriacaoToken = null,
        public ?int $dataExpirarToken = null,
        public ?int $excluido = null
    ) {
    }
}
