<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Hike;
use App\Entity\Locality;
use App\DataFixtures\LocalityFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class HikeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $localities = $manager->getRepository(Locality::class)->findAll();

        for ($i=0; $i < 12; $i++) { 
            $object = (new Hike())
                ->setName($faker->name)
                ->setDescription($faker->text)
                ->setDistance($faker->randomFloat(2, 1, 50))
                ->setDuration($faker->dateTimeInInterval('now', '+2 days'))
                ->setEffort($faker->randomNumber(1, 5))
                ->setMaxUsers($faker->randomNumber(1, 20))
                ->setLocality($localities[array_rand($localities)])
                ->setClub($this->getReference('club'))
            ;

            $manager->persist($object);

            $this->addReference('hike' . $i, $object);
        }

        for ($j=0; $j < 5; $j++) {             
            for ($i=0; $i < 5; $i++) { 
                $object = (new Hike())
                    ->setName($faker->name)
                    ->setDescription($faker->text)
                    ->setDistance($faker->randomFloat(2, 1, 50))
                    ->setDuration($faker->dateTimeInInterval('now', '+2 days'))
                    ->setEffort($faker->randomNumber(1, 5))
                    ->setMaxUsers($faker->randomNumber(1, 20))
                    ->setLocality($localities[array_rand($localities)])
                    ->setClub($this->getReference('club' . $j))
                ;
    
                $manager->persist($object);

                $this->addReference('hike' . $j . $i, $object);
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ClubFixtures::class,
            LocalityFixtures::class,
        ];
    }
}
