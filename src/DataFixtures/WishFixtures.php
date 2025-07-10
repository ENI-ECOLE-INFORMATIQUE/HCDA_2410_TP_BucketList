<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\User;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class WishFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);
        $faker = \Faker\Factory::create('en_EN');
        $users = $manager->getRepository(User::class)->findAll();

        for ($i = 1; $i <= 10; $i++) {
            $wish = new Wish();
            $wish->setTitle($faker->word);
            //$wish->setAuthor($faker->name);
            $wish->setUser($faker->randomElement($users));
            $wish->setDescription($faker->realText);

            $dateCreated = $faker->dateTimeBetween('-6 months','now');
            $wish->setDateCreated(\DateTimeImmutable::createFromMutable($dateCreated));

            $wish->setIsPublished($faker->numberBetween(0, 1));
            $wish->setCategory($this->getReference('c'.mt_rand(1,5), Category::class));
            $manager->persist($wish);

        }
        $manager->flush();
    }

    public function getDependencies(): array{
        return [CategoryFixtures::class,UserFixtures::class];
    }
}
