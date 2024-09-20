<?php

namespace App\Fixtures;

use App\Entity\User;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class UserDataLoader implements FixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $nbUsers = 8;
        for ($i = 0; $i < $nbUsers; $i++) {
            $user = new User();
            $user
                ->setEmail($faker->email())
                ->setPassword('password')
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setActive($faker->boolean(90))
            ;

            $manager->persist($user);
        }

        $manager->flush();
    }
}
