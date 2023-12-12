<?php

namespace Gso\Ws\Context\User\Domains\User\Interface;

use Gso\Ws\Context\User\Domains\User\Profile;

interface UserProfileRepositoryInterface
{
    public function saveNewUserProfile(Profile $profile): Profile;
}
