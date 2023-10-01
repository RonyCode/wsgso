<?php

namespace Gso\Ws\Shared\ValuesObjects;

use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

final class Phone
{
    use ResponseError;

    public function __construct(public ?string $phone = null)
    {
        try {
            if ($phone !== null) {
                if (! $this->validaPhone($phone)) {
                    throw new \RuntimeException();
                }
            }
        } catch (RuntimeException) {
            echo $this->responseCatchError('Telefone inválido!');
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
