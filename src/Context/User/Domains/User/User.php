<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Shared\ValuesObjects\Cpf;
use Gso\Ws\Shared\ValuesObjects\DateMysqlToFormatBr;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Shared\ValuesObjects\Senha;
use JsonException;

final readonly class User
{
    public function __construct(
        public ?int $codUsuario = null,
        public ?Cpf $cpf = null,
        public ?string $nome = null,
        public ?Email $email = null,
        public ?Senha $senha = null,
        public ?Senha $senhaExterna = null,
        public ?DateMysqlToFormatBr $dataCadastro = null,
        public ?string $image = null,
        public ?int $excluido = null
    ) {
    }

    /**
     * @throws JsonException
     */
    public static function userSerialize(
        ?int $codUsuario,
        ?string $cpf,
        ?string $nome,
        ?string $email,
        ?string $senha,
        ?string $senhaExterna,
        ?string $dataCadastro,
        ?string $image,
        ?int $excluido,
    ): self {
        return new User(
            $codUsuario,
            new Cpf($cpf),
            $nome,
            new Email($email),
            new Senha($senha),
            new Senha($senhaExterna),
            new DateMysqlToFormatBr($dataCadastro),
            $image,
            $excluido
        );
    }
}
