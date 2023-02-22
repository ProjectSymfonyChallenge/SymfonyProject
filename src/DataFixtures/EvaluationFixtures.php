<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Evaluation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EvaluationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $users = $manager->getRepository(User::class)->findAll();

        for ($j=0; $j < 5; $j++) {             
            for ($i=0; $i < 5; $i++) { 
                for ($k=0; $k < 20; $k++) { 
                    $object = (new Evaluation())
                        ->setUser($users[array_rand($users)])
                        ->setHike($this->getReference('hike' . $j . $i))
                        ->setEffort($faker->randomNumber(1, 5))
                        ->setDuration($faker->dateTimeInInterval('now', '+2 days'))
                        ->setBeauty($faker->randomNumber(1, 5))                 
                    ;
    
                    $manager->persist($object);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            ClubFixtures::class,
            HikeFixtures::class,
        ];
    }
}
