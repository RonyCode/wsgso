<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Context\User\Domains\User\Enums\RoleProfile;
use Gso\Ws\Shared\ValuesObjects\DateMysqlToFormatBr;
use JsonException;

final readonly class Profile
{
    public function __construct(
        public ?int $id = null,
        public ?string $role = null,
        public ?DateMysqlToFormatBr $dateGranted = null,
        public ?DateMysqlToFormatBr $dateExpires = null,
        public ?int $grantedByIdUser = null,
        public ?int $excluded = null
    ) {
    }

    /**
     * @throws JsonException
     */
    public function __clone(): void
    {
        $this->dateGranted = new DateMysqlToFormatBr($this->dateGranted);
        $this->dateExpires = new DateMysqlToFormatBr($this->dateExpires);
    }

    public function serializeProfile(): Profile
    {
        return clone $this;
    }
}
