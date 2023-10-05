<?php

declare(strict_types=1);

namespace Gso\Ws\Shared\ValuesObjects;

use DomainException;
use Exception;

final class Email
{
    private string $email;

    public function __construct(string $email = null)
    {
        try {
            if (null !== $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new DomainException('Email is not valid');
                }

                $this->email = $email;
            }
        } catch (DomainException) {
            echo json_encode([
                "code"    => 404,
                'status'  => 'ERROR',
                'message' => 'Formato de email nâo suportado',
            ], JSON_THROW_ON_ERROR | 64 | 256);
        }
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
