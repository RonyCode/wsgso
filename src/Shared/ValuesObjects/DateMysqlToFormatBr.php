<?php

declare(strict_types=1);

namespace Gso\Ws\Shared\ValuesObjects;

use Gso\Ws\Web\Helper\ResponseError;

final class DateMysqlToFormatBr
{
    private ?string $date;

    public function __construct(?string $date = null)
    {
        try {
            if (null !== $date && '' !== $date) {
                if (! $this->dateBr($date)) {
                    throw new \DomainException('Formato de data deve seguir exatamente este formato 9999-99-99');
                }
                $this->date = $date;
            }
        } catch (\DomainException $e) {
            echo json_encode([
                "status"  => "ERROR",
                "message" => $e->getMessage()
            ], JSON_THROW_ON_ERROR);
            exit();
        }
    }

    public function __toString(): string
    {
        return $this->date ?? '';
    }

    public function dateBr(?string $date = null): string|null
    {
        if ($date === null) {
            return null;
        }
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
