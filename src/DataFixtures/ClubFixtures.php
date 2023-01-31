<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Club;
use App\Entity\User;
use App\Entity\Level;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ClubFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $levels = $manager->getRepository(Level::class)->findAll();

        $userRepository = $manager->getRepository(User::class);
        $userManager = $userRepository->findOneBy(['email' => 'gerant@user.fr']);

        $object = (new Club())
            ->setName('Club Test')
            ->setDescription('Description du club test')
            ->setAdresse('1 rue de la paix')
            ->setManager($userManager)
        ;
        
        $manager->persist($object);

        $this->addReference('club', $object);

        for ($i=0; $i < 5; $i++) { 
            $object = (new Club())
                ->setName($faker->company)
                ->setDescription($faker->text)
                ->setAdresse($faker->address)
                ->setManager($this->getReference('user' . $i))
            ;

            $manager->persist($object);

            $this->addReference('club' . $i, $object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            UserFixtures::class,
        ];
    }
}
