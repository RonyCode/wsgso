<?php


namespace Gso\Ws\App\UseCases\UsuarioExternoAuthCase;

use Gso\Ws\Domains\ValuesObjects\Cpf;
use Gso\Ws\Domains\ValuesObjects\Email;
use Gso\Ws\Domains\ValuesObjects\Senha;

readonly class OutputBoundaryUsuarioExternoAuth
{
    public function __construct(
        public ?int $codUsuario = null,
        public ?Cpf $cpf = null,
        public ?string $nome = null,
        public ?Email $email = null,
        public ?Senha $senha = null,
        public ?string $senhaExtera = null,
        public ?string $dataCadastro = null,
        public ?string $image = null,
        public ?int $excluido = null
    ) {
    }
}
