<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Shared\ValuesObjects\DateMysqlToFormatBr;
use JsonException;

final readonly class Profile
{
    public function __construct(
        public ?int $id = null,
        public ?string $role = null,
        public ?string $dateGranted = null,
        public ?string $dateExpires = null,
        public ?int $grantedByIdUser = null,
        public ?int $excluded = null
    ) {
        clone $this;
    }

    /**
     * @throws JsonException
     */
    public function __clone(): void
    {
        $this->dateGranted = (string)new DateMysqlToFormatBr($this->dateGranted);
        $this->dateExpires = (string)new DateMysqlToFormatBr($this->dateExpires);
    }

    public function sanitize(): Profile
    {
        return clone $this;
    }
}
