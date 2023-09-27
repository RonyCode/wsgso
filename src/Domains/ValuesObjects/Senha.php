<?php

declare(strict_types=1);

namespace Gso\Ws\Domains\ValuesObjects;

use RuntimeException;

final class Senha
{
    private string $senha;

    public function __construct(string $senha = null)
    {
        try {
            if (null !== $senha) {
                if ( ! $this->validatePass($senha)) {
                    throw new RuntimeException();
                }
                $this->senha = $senha;
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
        if ( ! preg_match($regex, $pass, $match)) {
            return false;
        }

        return true;
    }

    public function __toString(): string
    {
        return $this->senha;
    }
}
