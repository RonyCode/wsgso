<?php


namespace Gso\Ws\Context\User\App\UseCases\UserExternal\SignInUserExternal;

use Gso\Ws\Shared\ValuesObjects\Cpf;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Shared\ValuesObjects\Pass;

readonly class OutputBoundaryUserExternal
{
    public function __construct(
        public ?int $codUsuario = null,
        public ?Cpf $cpf = null,
        public ?string $nome = null,
        public ?Email $email = null,
        public ?Pass $senha = null,
        public ?string $senhaExtera = null,
        public ?string $dataCadastro = null,
        public ?string $image = null,
        public ?int $excluido = null
    ) {
    }
}
