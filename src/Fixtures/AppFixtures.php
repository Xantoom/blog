<?php

namespace App\Fixtures;

use App\Entity\User;
use App\enums\Roles;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class AppFixtures implements FixtureInterface
{
    private Generator $faker;
    private ObjectManager $manager;
    private array $users = [];

    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create('fr_FR');
        $this->manager = $manager;

        $this->createUsers();
        $this->createPersistentUsers();

        $manager->flush();
    }

    private function createUsers(): void
    {
        $nbUsers = 8;
        for ($i = 0; $i < $nbUsers; $i++) {
            $user = new User();
            $user
                ->setEmail($this->faker->email())
                ->setPassword('password')
                ->setFirstname($this->faker->firstName())
                ->setLastname($this->faker->lastName())
                ->setCreatedAt(new \DateTimeImmutable())
                ->setActive($this->faker->boolean(90))
            ;
            $this->manager->persist($user);
            $this->users[] = $user;
        }
    }

    private function createPersistentUsers(): void
    {
        // ADMIN
        $admin = new User();
        $admin
            ->setEmail('admin@test.com')
            ->setPassword('password')
            ->setFirstname('Admin')
            ->setLastname('Admin')
            ->setRoles([Roles::ROLE_ADMIN, Roles::ROLE_EDITOR, Roles::ROLE_USER])
        ;
        $this->manager->persist($admin);
        $this->users[] = $admin;

        // EDITOR
        $editor = new User();
        $editor
            ->setEmail('editor@test.com')
            ->setPassword('password')
            ->setFirstname('Editor')
            ->setLastname('Editor')
            ->setRoles([Roles::ROLE_EDITOR, Roles::ROLE_USER])
        ;
        $this->manager->persist($editor);
        $this->users[] = $editor;

        // USER
        $user = new User();
        $user
            ->setEmail('user@test.com')
            ->setPassword('password')
            ->setFirstname('User')
            ->setLastname('User')
            ->setRoles([Roles::ROLE_USER])
        ;
        $this->manager->persist($user);
        $this->users[] = $user;
    }
}
