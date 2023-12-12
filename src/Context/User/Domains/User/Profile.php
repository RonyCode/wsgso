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
    public static function profileSerialize(
        ?int $id = null,
        ?string $role = null,
        ?string $dateGranted = null,
        ?string $dateExpires = null,
        ?int $grantedByIdUser = null,
        ?int $excluded = null
    ): self {
        return new Profile(
            $id,
            (RoleProfile::from($role))->name,
            new DateMysqlToFormatBr($dateGranted),
            new DateMysqlToFormatBr($dateExpires),
            $grantedByIdUser,
            $excluded
        );
    }
}
