<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\User;

class UserMother
{
    public static function plainUser(): User
    {
        return new User();
    }
}