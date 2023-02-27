<?php

namespace App\DataFixtures;

use App\Entity\Picture;
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
            $picture = (new Picture())
            ->setFilename('https://picsum.photos/640/480?random=1')
            ->setType('hike');

            $picture2 = (new Picture())
                ->setFilename('https://picsum.photos/640/480?random=1')
                ->setType('hike');

            $object = (new Hike())
                ->setName($faker->name)
                ->setDescription($faker->text)
                ->setDistance($faker->randomFloat(2, 1, 50))
                ->setDuration($faker->dateTimeInInterval('now', '+2 days'))
                ->setEffort($faker->randomNumber(1, 5))
                ->setMaxUsers($faker->randomNumber(1, 20))
                ->setLocality($localities[array_rand($localities)])
                ->setLongitude($faker->longitude( -5.1, 9.6))
                ->setLatitude($faker->latitude( 41.2, 51.1))
                ->addPicture($picture)
                ->addPicture($picture2)
                ->setClub($this->getReference('club'));

            $manager->persist($object);
            $manager->persist($picture);
            $manager->persist($picture2);
        }

        for ($j=0; $j < 5; $j++) {             
            for ($i=0; $i < 5; $i++) { 
                $picture = (new Picture())
                    ->setFilename('https://picsum.photos/640/480?random=1')
                    ->setType('hike');

                $picture2 = (new Picture())
                    ->setFilename('https://picsum.photos/640/480?random=1')
                    ->setType('hike');

                $object = (new Hike())
                    ->setName($faker->name)
                    ->setDescription($faker->text)
                    ->setDistance($faker->randomFloat(2, 1, 50))
                    ->setDuration($faker->dateTimeInInterval('now', '+2 days'))
                    ->setEffort($faker->randomNumber(1, 5))
                    ->setMaxUsers($faker->randomNumber(1, 20))
                    ->setLocality($localities[array_rand($localities)])
                    ->setLongitude($faker->longitude( -5.1, 9.6))
                    ->setLatitude($faker->latitude( 41.2, 51.1))
                    ->addPicture($picture)
                    ->addPicture($picture2)
                    ->setClub($this->getReference('club' . $j));
    
                $manager->persist($object);
                $manager->persist($picture);
                $manager->persist($picture2);

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
