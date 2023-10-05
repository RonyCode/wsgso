<?php

declare(strict_types=1);

namespace Gso\Ws\Context\User\Domains\User;

use Gso\Ws\Shared\ValuesObjects\Cpf;
use Gso\Ws\Shared\ValuesObjects\DateMysqlToFormatBr;
use Gso\Ws\Shared\ValuesObjects\Email;
use Gso\Ws\Shared\ValuesObjects\Phone;
use JsonException;

final readonly class Profile
{
    public function __construct(
        public ?string $role = null,
        public ?DateMysqlToFormatBr $dateGranted = null,
        public ?DateMysqlToFormatBr $dateExpires = null,
        public ?int $grantedByUser = null,
        public ?int $excluded = null
    ) {
    }

    /**
     * @throws JsonException
     */
    public static function profileSerialize(
        string $role,
        ?string $dateGranted,
        ?string $dateExpires,
        ?int $grantedByUser,
        ?int $excluded
    ): self {
        return new Profile(
            (RoleProfile::from($role))->name,
            new DateMysqlToFormatBr($dateGranted),
            new DateMysqlToFormatBr($dateExpires),
            $grantedByUser,
            $excluded
        );
    }
}
