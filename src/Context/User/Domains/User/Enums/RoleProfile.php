<?php

namespace Gso\Ws\Context\User\Domains\User\Enums;

enum RoleProfile: string
{
    case USER = 'user';
    case MANAGER = 'manager';
    case ADMIN = 'admin';
    case SUPERADMIN = 'superadmin';
}
