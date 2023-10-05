<?php

namespace Gso\Ws\Shared\ValuesObjects;

use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

final class Cep
{
    public function __construct(public ?string $cep = null)
    {
        try {
            if (($this->cep !== null && $this->cep !== '') && ! $this->validaCep($cep)) {
                throw new \RuntimeException();
            }
        } catch (RuntimeException) {
            echo json_encode([
                "code"    => 404,
                'status'  => 'ERROR',
                'message' => 'Cep inválido',
            ], JSON_THROW_ON_ERROR | 64 | 256);
        }
    }

    public function validaCep($cep = null): bool
    {
        return preg_match('/^[0-9]{8}$/', $cep);
    }

    public function __toString(): string
    {
        return $this->cep ?? '';
    }
}
