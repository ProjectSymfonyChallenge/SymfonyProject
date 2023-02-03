<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Payment;
use App\DataFixtures\BookingFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class PaymentFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($j=0; $j < 5; $j++) {             
            for ($i=0; $i < 5; $i++) { 
                for ($k=0; $k < 5; $k++) { 
                    $object = (new Payment())
                        ->setBooking($this->getReference('booking' . $j . $i . $k))
                        ->setPrice($faker->randomFloat(2, 1, 400))
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
            BookingFixtures::class,
        ];
    }
}
