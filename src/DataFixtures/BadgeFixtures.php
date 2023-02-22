<?php

namespace App\DataFixtures;

use App\Entity\Badge;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class BadgeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $object = (new Badge())
            ->setName('Bienvenue')
            ->setDescription('Bienvenue sur le site de Peak Experience')
            ->setExperiencePoints(200)
        ;

        $manager->persist($object);

        $this->addReference('badgeWelcome', $object);

        $object = (new Badge())
            ->setName('Auteur débutant')
            ->setDescription('Premier commentaire sur un parcours')
            ->setExperiencePoints(500)
        ;

        $manager->persist($object);

        $this->addReference('badgeFirstComment', $object);

        $object = (new Badge())
            ->setName('Auteur confirmé')
            ->setDescription('10 commentaires sur des randonnées')
            ->setExperiencePoints(5000)
        ;

        $manager->persist($object);

        $this->addReference('badge10Comments', $object);

        $object = (new Badge())
            ->setName('Auteur expert')
            ->setDescription('50 commentaires sur des randonnées')
            ->setExperiencePoints(30000)
        ;

        $manager->persist($object);

        $this->addReference('badge50Comments', $object);

        
        $object = (new Badge())
        ->setName('Randonneur débutant')
        ->setDescription('Première randonnée réalisée')
        ->setExperiencePoints(2000)
        ;
        
        $manager->persist($object);
        
        $this->addReference('badgeFirstHike', $object);

        $object = (new Badge())
            ->setName('Randonneur confirmé')
            ->setDescription('10 randonnées réalisées')
            ->setExperiencePoints(30000)
        ;

        $manager->persist($object);

        $this->addReference('badge10Hikes', $object);

        $object = (new Badge())
            ->setName('Randonneur expert')
            ->setDescription('50 randonnées réalisées')
            ->setExperiencePoints(200000)
        ;

        $manager->persist($object);

        $this->addReference('badge50Hikes', $object);

        $object = (new Badge())
            ->setName('Jambes de gazelle')
            ->setDescription('100 km parcourus')
            ->setExperiencePoints(50000)
        ;

        $manager->persist($object);

        $this->addReference('badge100Km', $object);

        $object = (new Badge())
            ->setName('Jambes de cheval')
            ->setDescription('500 km parcourus')
            ->setExperiencePoints(200000)
        ;

        $manager->persist($object);

        $this->addReference('badge500Km', $object);

        $object = (new Badge())
            ->setName('Jambes de marathonien')
            ->setDescription('2000 km parcourus')
            ->setExperiencePoints(1000000)
        ;

        $manager->persist($object);

        $this->addReference('badge2000Km', $object);

        $object = (new Badge())
            ->setName('Jambes en feu')
            ->setDescription('10000 km parcourus')
            ->setExperiencePoints(5000000)
        ;

        $manager->persist($object);

        $this->addReference('badge10000Km', $object);

        $object = (new Badge())
            ->setName('Gérant de club')
            ->setDescription('Etre gérant d\'un club')
            ->setExperiencePoints(10000)
        ;

        $manager->persist($object);

        $this->addReference('badgeClubManager', $object);

        $manager->flush();
    }
}
