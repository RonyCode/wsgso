<?php

namespace Gso\Ws\Context\User\App\UseCases\User\SignInUser;

use Gso\Ws\Shared\ValuesObjects\Cpf;
use Gso\Ws\Shared\ValuesObjects\DateMysqlToFormatBr;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Shared\ValuesObjects\Pass;

readonly class OutputBoundaryUserSignIn
{
    public function __construct(
        public ?int $codUsuario = null,
        public ?Cpf $cpf = null,
        public ?string $nome = null,
        public ?Email $email = null,
        public ?Pass $senha = null,
        public ?DateMysqlToFormatBr $dataCadastro = null,
        public ?string $image = null,
        public ?string $token = null,
        public ?string $refreshToken = null,
        public ?int $dataCriacaoToken = null,
        public ?int $dataExpirarToken = null,
        public ?int $excluido = null
    ) {
    }
}
