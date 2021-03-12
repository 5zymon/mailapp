<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserAndContactsFixtures extends Fixture
{
    private const NUMBER_OF_USERS = 5;
    private const NUMBER_OF_CONTACTS_PER_USER = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($users = 1; $users <= self::NUMBER_OF_USERS; $users++) {
            $user = new User();
            $user->setName($faker->name);
            $manager->persist($user);

            for ($contacts = 1; $contacts <= self::NUMBER_OF_CONTACTS_PER_USER; $contacts++) {
                $contact = new Contact();
                $contact->setUser($user);
                $contact->setName($faker->company);
                $contact->setEmail($faker->email);
                $manager->persist($contact);
            }
        }

        $manager->flush();
    }
}
