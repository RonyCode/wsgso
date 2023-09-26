<?php

declare(strict_types=1);

namespace Gso\Ws\Domains\Models;

use Gso\Ws\Domains\ValuesObjects\Cpf;
use Gso\Ws\Domains\ValuesObjects\Email;
use Gso\Ws\Domains\ValuesObjects\Senha;

final readonly class UsuarioModel
{
    public function __construct(
        public ?int $codUsuario = null,
        public ?Cpf $cpf = null,
        public ?string $nome = null,
        public ?Email $email = null,
        public ?Senha $senha = null,
        public ?string $senhaExterna = null,
        public ?string $dataCadastro = null,
        public ?string $image = null,
        public ?int $excluido = null
    ) {
    }

    public function usuarioCpf()
    {
    }
}
