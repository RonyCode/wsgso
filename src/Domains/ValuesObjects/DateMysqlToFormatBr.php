<?php

declare(strict_types=1);

namespace Gso\Ws\Domains\ValuesObjects;

use Gso\Ws\Web\Helper\ResponseError;

final class DateMysqlToFormatBr
{
    use ResponseError;

    public function __construct(private string $date)
    {
        try {
            if (null === $this->date) {
                throw new \RuntimeException();
            }

            $dateFormated = $this->dateBr($date);
            $this->date = $dateFormated;
        } catch (\RuntimeException $e) {
            $this->responseCatchError('Formato de data deve seguir exatamente este formato 9999-99-99');
        }
    }

    public function __toString(): string
    {
        return $this->date;
    }

    public function dateBr(string $date): string|null
    {
        $dateInput = \DateTimeImmutable::createFromFormat('Y-m-d', $date);
        if ($dateInput) {
            return $dateInput->format('d/m/Y');
        }
        $dateInputTime = \DateTimeImmutable::createFromFormat('Y-m-d H:i:s', $date);
        if ($dateInputTime) {
            return $dateInputTime->format('d/m/Y H:i:s');
        }

        return null;
    }
}
