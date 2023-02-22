<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Booking;
use App\DataFixtures\HikeFixtures;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class BookingFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $manager->getRepository(User::class)->findAll();

        for ($j=0; $j < 5; $j++) {             
            for ($i=0; $i < 5; $i++) { 
                for ($k=0; $k < 5; $k++) { 
                    $object = (new Booking())
                        ->setUser($users[array_rand($users)])
                        ->setHike($this->getReference('hike' . $j . $i))
                        ->setHikeDate($faker->dateTimeBetween('now', '+1 month'))
                    ;

                    $manager->persist($object);

                    $this->addReference('booking' . $j . $i . $k, $object);
                }
            }
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
            HikeFixtures::class,
        ];
    }
}
