<?php

namespace App\DataFixtures;

use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TrickFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 30; $i++){
            $trick = new Trick();
            $trick->setTitle("Trick name n°$i")
                ->setContent("<p>Content trick n°$i</p>")
                ->setImage("http://placehold.it/1920x500")
                ->setCreatedAt(new \DateTime())
                ->setModifyAt(new \DateTime());

            $manager->persist($trick);
        }

        $manager->flush();
    }
}
