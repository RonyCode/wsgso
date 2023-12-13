<?php

declare(strict_types=1);

namespace Gso\Ws\Shared\ValuesObjects;

use DomainException;
use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

final class Cpf
{
    public function __construct(private ?string $cpf = null)
    {
        try {
            if (($cpf !== null && $cpf !== '')) {
                if (! $this->validateCPF($cpf)) {
                    throw new \DomainException();
                }
            }
        } catch (DomainException) {
            echo json_encode([
                "code"    => 404,
                'status'  => 'ERROR',
                'message' => 'Cpf inválido',
            ], JSON_THROW_ON_ERROR | 64 | 256);
            exit();
        }
    }

    public function __toString(): string
    {
        $cpfSanitarized = preg_replace('/[\D]{0,9}/', '', $this->cpf);

        return $this->cpf = $cpfSanitarized ?? '';
    }

    private function validateCPF($number): bool
    {
        $cpf = preg_replace('/[^0-9]/', "", $number);

        if (strlen($cpf) != 11 || preg_match('/([0-9])\1{10}/', $cpf)) {
            return false;
        }

        $number_quantity_to_loop = [9, 10];

        foreach ($number_quantity_to_loop as $item) {
            $sum                    = 0;
            $number_to_multiplicate = $item + 1;

            for ($index = 0; $index < $item; $index++) {
                $sum += $cpf[$index] * ($number_to_multiplicate--);
            }

            $result = (($sum * 10) % 11);

            if ($cpf[$item] != $result) {
                return false;
            }
        }


        return true;
    }
}
