<?php

declare(strict_types=1);

namespace Gso\Ws\Shared\ValuesObjects;

use DomainException;

final class Cep
{
    public function __construct(private ?string $cep = null)
    {
        try {
            if (($cep !== null && $cep !== '') && ! $this->validaCep($cep)) {
                throw new \DomainException();
            }
        } catch (DomainException) {
            echo json_encode([
                "code"    => 404,
                'status'  => 'ERROR',
                'message' => 'Cep inválido',
            ], JSON_THROW_ON_ERROR | 64 | 256);
            exit();
        }
    }

    public function validaCep(?string $cep = null): bool
    {
        return preg_match('/^[0-9]{5}\-?[0-9]{3}$/', $cep) === 1;
    }

    public function __toString(): string
    {
        return $this->cep ?? '';
    }
}
