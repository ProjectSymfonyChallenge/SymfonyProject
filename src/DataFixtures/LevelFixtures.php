<?php

namespace App\DataFixtures;

use App\Entity\Level;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LevelFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $object = (new Level())
            ->setName('Débutant')
            ->setRange('0-10')
        ;

        $manager->persist($object);

        $object = (new Level())
            ->setName('Intermédiaire')
            ->setRange('11-50')
        ;

        $manager->persist($object);

        $object = (new Level())
            ->setName('Confirmé')
            ->setRange('51')
        ;

        $manager->persist($object);

        $manager->flush();
    }
}
