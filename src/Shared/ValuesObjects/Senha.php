<?php

declare(strict_types=1);

namespace Gso\Ws\Shared\ValuesObjects;

use RuntimeException;

final class Senha
{
    public function __construct(public readonly ?string $senha = null)
    {
        try {
            if (null !== $this->senha) {
                if (! $this->validatePass($this->senha)) {
                    throw new RuntimeException();
                }
            }
        } catch (RuntimeException) {
            echo json_encode([
                "status"  => "ERROR",
                "message" => "senha deve conter no mínimo 8 caracteres e ao menos 1 letra "
            ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | 64);
        }
    }

    public function validatePass(string $pass): bool
    {
        $regex = "/^\S*(?=\S{8,})(?=\S*[a-zA-Z])(?=\S*[\d])\S*$/";
        if (! preg_match($regex, $pass, $match)) {
            return false;
        }



        return true;
    }

    public function __toString(): string
    {
        return $this->senha ?? '';
    }
}
