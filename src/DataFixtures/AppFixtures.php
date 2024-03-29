<?php

namespace App\DataFixtures;

use App\Entity\Comment;
use App\Entity\Post;
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
        $users = [];
        $users[] = $admin;
        for ($i = 0; $i < 5; $i++) {
            $user = new User();
            $user->setEmail($faker->email())
                ->setPassword($this->hasher->hashPassword($user, 'password'))
                ->setName($faker->firstName())
                ->setImg('random-user.png')
                ->setBiography($faker->text());

            $users[] = $user;
            $manager->persist($user);
        }

        //Post
        $posts = [];
        for ($i = 0; $i < 50; $i++) {
            $post = new Post();
            $post->setCreatedAt(new \DateTimeImmutable())
                ->setContent($faker->text())
                ->setUser($users[rand(0, 5)]);
            $posts[] = $post;
            $manager->persist($post);
        }

        //Comment
        for ($i = 0; $i < 50; $i++) {
            $comment = new Comment();
            $comment->setCreatedAt(new \DateTimeImmutable())
                ->setContent($faker->text())
                ->setUser($users[rand(0, 5)])
                ->setPost($posts[rand(0, 49)]);

            $manager->persist($comment);
        }


        $manager->flush();
    }
}
