<?php

namespace Gso\Ws\App\UseCases\UsuarioAuthCase;

use Gso\Ws\Domains\ValuesObjects\Cpf;
use Gso\Ws\Domains\ValuesObjects\Email;
use Gso\Ws\Domains\ValuesObjects\Senha;

readonly class OutputBoundaryUsuarioAuth
{
    public function __construct(
        public ?int $codUsuario = null,
        public ?Cpf $cpf = null,
        public ?string $nome = null,
        public ?Email $email = null,
        public ?Senha $senha = null,
        public ?string $dataCadastro = null,
        public ?string $image = null,
        public ?string $token = null,
        public ?string $refreshToken = null,
        public ?int $dataCriacaoToken = null,
        public ?int $dataExpirarToken = null,
        public ?int $excluido = null
    ) {
    }
}
