<?php

declare(strict_types=1);

namespace Gso\Ws\Shared\ValuesObjects;

use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

final class Cpf
{
    private ?string $cpf;

    public function __construct(?string $cpf = null)
    {
        try {
            if (($cpf !== null && $cpf !== '') && ! $this->validaCPF($cpf)) {
                throw new \RuntimeException();
            }
        } catch (RuntimeException) {
            echo json_encode([
                "code"    => 404,
                'status'  => 'ERROR',
                'message' => 'Cpf inválido',
            ], JSON_THROW_ON_ERROR | 64 | 256);
        }
    }

    public function __toString(): string
    {
        return $this->cpf ?? '';
    }

    public function validaCPF(string $cpf = null): bool
    {
        if (null === $cpf) {
            return false;
        }

        // Extrai somente os números
        if ($cpf) {
            $cpf = preg_replace('/[^0-9]/is', '', $cpf);

            // Verifica se foi informado todos os digitos corretamente
            if (11 != strlen($cpf)) {
                return false;
            }

            // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
            if (preg_match('/(\d)\1{10}/', $cpf)) {
                return false;
            }
            // Faz o calculo para validar o CPF
            for ($t = 9; $t < 11; ++$t) {
                for ($d = 0, $c = 0; $c < $t; ++$c) {
                    $d += $cpf[$c] * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf[$c] != $d) {
                    return false;
                }
            }
        }

        return true;
    }
}
