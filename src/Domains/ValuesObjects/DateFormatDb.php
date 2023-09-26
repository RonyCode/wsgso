<?php

declare(strict_types=1);

namespace Gso\Ws\Domains\ValuesObjects;

use DateTimeImmutable;
use RuntimeException;

final class DateFormatDb
{
    private string $date;

    public function __construct(string $date)
    {
        try {
            if (!$dateFormated = $this->dateToDb($date)) {
                throw new RuntimeException("Formato de data deve seguir exatamente este formato 99/99/9999");
            }
            $this->date = $dateFormated;
        } catch (RuntimeException $e) {
            echo json_encode([
                "status" => "ERROR",
                "message" => $e->getMessage()
            ], JSON_THROW_ON_ERROR);
        }
    }

    public function dateToDb(string $date): string|null
    {
        $dateInput = DateTimeImmutable::createFromFormat('d/m/Y', $date);
        if ($dateInput) {
            return $dateInput->format("Y-m-d");
        }
        $dateInputTime = DateTimeImmutable::createFromFormat('d/m/Y H:i:s', $date);
        if ($dateInputTime) {
            return $dateInputTime->format("Y-m-d H:i:s");
        }
        return null;
    }

    public function __toString(): string
    {
        return $this->date;
    }
}
