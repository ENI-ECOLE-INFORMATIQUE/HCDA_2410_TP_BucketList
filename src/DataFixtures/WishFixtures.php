<?php

namespace App\DataFixtures;

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
        for ($i = 1; $i <= 10; $i++) {
            $wish = new Wish();
            $wish->setTitle($faker->word);
            $wish->setAuthor($faker->name);
            $wish->setDescription($faker->realText);

            $dateCreated = $faker->dateTimeBetween('-6 months','now');
            $wish->setDateCreated(\DateTimeImmutable::createFromMutable($dateCreated));

            $wish->setIsPublished($faker->numberBetween(0, 1));
            $manager->persist($wish);

        }
        $manager->flush();
    }
}
