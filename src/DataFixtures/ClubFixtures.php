<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Club;
use App\Entity\User;
use App\Entity\Picture;
use App\DataFixtures\UserFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ClubFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $userRepository = $manager->getRepository(User::class);
        $userManager = $userRepository->findOneBy(['email' => 'gerant@user.fr']);

        $picture = (new Picture())
            ->setFilename($faker->imageUrl(640, 480, 'club'))
            ->setType('club');

        $manager->persist($picture);

        $object = (new Club())
            ->setName('Club Test')
            ->setDescription('Description du club test')
            ->setAdresse('1 rue de la paix')
            ->setManager($userManager)
            ->addPicture($picture)
        ;
        
        $manager->persist($object);

        $this->addReference('club', $object);

        for ($i=0; $i < 5; $i++) { 
            $picture = (new Picture())
                ->setFilename('https://picsum.photos/640/480?random=1')
                ->setType('club');

            $manager->persist($picture);

            $object = (new Club())
                ->setName($faker->company)
                ->setDescription($faker->text)
                ->setAdresse($faker->address)
                ->setManager($this->getReference('user' . $i))
                ->addPicture($picture)
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
