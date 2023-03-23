<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {
        $this->hasher = $hasher;
    }
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();


        //User
        $admin = new User();
        $admin->setRoles(['ROLE_ADMIN'])
            ->setEmail('jerem@gmail.com')
            ->setPassword($this->hasher->hashPassword($admin, 'password'))
            ->setName('Jerem');

        $manager->persist($admin);

        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setName($faker->name());
            $manager->persist($user);
        }


        $manager->flush();
    }
}
