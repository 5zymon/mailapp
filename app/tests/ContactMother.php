<?php

declare(strict_types=1);

namespace App\Tests;

use App\Entity\Contact;
use App\Entity\User;

final class ContactMother
{
    public static function withUser(User $user): Contact
    {
        $contact = new Contact();
        $contact->setEmail('example@email.cpom');
        $contact->setName('some name');
        $contact->setUser($user);

        return $contact;
    }
}