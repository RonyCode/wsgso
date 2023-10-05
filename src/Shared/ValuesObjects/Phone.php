<?php

namespace Gso\Ws\Shared\ValuesObjects;

use RuntimeException;

final class Phone
{
    public function __construct(public ?string $phone = null)
    {
        try {
            if ($phone !== null && $phone !== '') {
                if (! $this->validaPhone($phone)) {
                    throw new \RuntimeException();
                }
            }
        } catch (RuntimeException) {
            echo json_encode([
                "code"    => 404,
                'status'  => 'ERROR',
                'message' => 'Telefone inválido',
            ], JSON_THROW_ON_ERROR | 64 | 256);
        }
    }

    public function validaPhone($phone = null): bool
    {
        return preg_match('/^[0-9]{11}$/', $phone);
    }

    public function __toString(): string
    {
        return $this->phone ?? '';
    }
}
