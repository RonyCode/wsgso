<?php

namespace Gso\Ws\Shared\ValuesObjects;

use Gso\Ws\Web\Helper\ResponseError;
use RuntimeException;

final class Cep
{
    use ResponseError;

    public function __construct(public ?string $cep = null)
    {
        try {
            if ($cep !== null) {
                if (! $this->validaCep($cep)) {
                    throw new \RuntimeException();
                }
            }
        } catch (RuntimeException) {
            echo $this->responseCatchError('Cep inválido!');
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
