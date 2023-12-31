<?php

declare(strict_types=1);

namespace Gso\Ws\Shared\ValuesObjects;

use DomainException;
use RuntimeException;

final readonly class Pass
{
    public function __construct(public ?string $senha = null)
    {
        try {
            if (null !== $this->senha && '' !== $this->senha) {
                if (! $this->validatePass($this->senha)) {
                    throw new \DomainException();
                }
            }
        } catch (DomainException) {
            echo json_encode([
                "status"  => "ERROR",
                "message" => "senha deve conter no mínimo 8 caracteres e ao menos 1 letra "
            ], JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | 64);
            exit();
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
