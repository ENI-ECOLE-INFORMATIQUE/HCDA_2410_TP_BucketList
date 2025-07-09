<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Wish;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $c1 = new Category();
        $c1->setName('Travel & Adventure');
        $manager->persist($c1);
        $this->addReference('c1', $c1);

        $c2 = new Category();
        $c2->setName('Sport');
        $manager->persist($c2);
        $this->addReference('c2', $c2);

        $c3 = new Category();
        $c3->setName('Entertainment');
        $manager->persist($c3);
        $this->addReference('c3', $c3);

        $c4 = new Category();
        $c4->setName('Human Relations');
        $manager->persist($c4);
        $this->addReference('c4', $c4);

        $c5 = new Category();
        $c5->setName('Others');
        $manager->persist($c5);
        $this->addReference('c5', $c5);


        $manager->flush();
    }
}
