<?php

declare(strict_types=1);

namespace Gso\Ws\Domains\ValuesObjects;

use Exception;

final class Email
{
    private string $email;

    public function __construct(string $email)
    {
        try {
            if (null != $email) {
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    throw new \DomainException('Email is not valid');
                }

                $this->email = $email;
            }
        } catch (Exception) {
            echo json_encode([
                'status' => 'ERROR',
                'message' => 'Formato de email nâo suportado',
            ], JSON_THROW_ON_ERROR | 64 | 256);
        }
    }

    public function __toString(): string
    {
        return $this->email;
    }
}
