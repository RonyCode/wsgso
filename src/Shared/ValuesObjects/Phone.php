<?php

namespace Gso\Ws\Shared\ValuesObjects;

use DomainException;
use RuntimeException;

final class Phone
{
    public function __construct(private ?string $phone = null)
    {
        try {
            if ($phone !== null && $phone !== '' && ! $this->validaPhone($phone)) {
                throw new \DomainException();
            }
        } catch (DomainException) {
            echo json_encode([
                "code"    => 404,
                'status'  => 'ERROR',
                'message' => 'Formato de telefone inválido deverá ser (99) 99999-9999',
            ], JSON_THROW_ON_ERROR | 64 | 256);
            exit();
        }
    }

    public function validaPhone($phone = null): bool
    {
        if (preg_match('/^\(?\d{2}\)?\s?\d{5}\-?\d{4}$/', $phone) === 1) {
            $phone       = preg_replace('/\D{0,9}/', '', $phone);
            $this->phone = $phone;

            return true;
        }

        return preg_match('/^\(?\d{2}\)?\s?\d{5}\-?\d{4}$/', $phone) === 1;
    }

    public function __toString(): string
    {
        return $this->phone ?? '';
    }
}
