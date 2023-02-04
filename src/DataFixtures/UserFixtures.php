<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Level;
use App\Entity\Locality;
use App\DataFixtures\BadgeFixtures;
use App\DataFixtures\LevelFixtures;
use App\DataFixtures\LocalityFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // pwd = Test1234
        $pwd = '$2y$13$0fWxLbIeHztLelSkgXLLAONeAD3e7MzH5ntmw4bJYtBQEsRinTNIO';

        $levels = $manager->getRepository(Level::class)->findAll();
        $localities = $manager->getRepository(Locality::class)->findAll();

        $object = (new User())
            ->setLevel($levels[array_rand($levels)])
            ->setLocality($localities[array_rand($localities)])
            ->setEmail('admin@user.fr')
            ->setUsername('admin')
            ->setFirstname('admin')
            ->setLastname('admin')
            ->setStatus(TRUE)
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($pwd)
            ->addBadge($this->getReference('badgeWelcome'))
            ->addBadge($this->getReference('badgeFirstComment'))
            ->addBadge($this->getReference('badge10Comments'))
            ->addBadge($this->getReference('badge50Comments'))
            ->addBadge($this->getReference('badgeFirstHike'))
            ->addBadge($this->getReference('badge10Hikes'))
            ->addBadge($this->getReference('badge50Hikes'))
            ->addBadge($this->getReference('badge100Km'))
            ->addBadge($this->getReference('badge500Km'))
            ->addBadge($this->getReference('badge2000Km'))
            ->addBadge($this->getReference('badge10000Km'))
            ->addBadge($this->getReference('badgeClubManager'))
        ;

        $manager->persist($object);

        $object = (new User())
            ->setLevel($levels[array_rand($levels)])
            ->setLocality($localities[array_rand($localities)])
            ->setEmail('gerant@user.fr')
            ->setUsername('Gerant')
            ->setFirstname('Gerant')
            ->setLastname('Gerant')
            ->setStatus(TRUE)
            ->setRoles(['ROLE_GERANT'])
            ->setPassword($pwd)
        ;
        $manager->persist($object);

        $object = (new User())
            ->setLevel($levels[array_rand($levels)])
            ->setLocality($localities[array_rand($localities)])
            ->setEmail('guide@user.fr')
            ->setUsername('Guide')
            ->setFirstname('Guide')
            ->setLastname('Guide')
            ->setStatus(TRUE)
            ->setRoles(['ROLE_GUIDE'])
            ->setPassword($pwd)
        ;
        $manager->persist($object);

        for ($i = 0; $i < 5; $i++) {
            $object = (new User())
                ->setLevel($levels[array_rand($levels)])
                ->setLocality($localities[array_rand($localities)])
                ->setEmail('gerant' . $i . '@user.fr')
                ->setUsername($faker->userName)
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setStatus(TRUE)
                ->setRoles(['ROLE_GERANT'])
                ->setPassword($pwd)
            ;
            $manager->persist($object);

            $this->addReference('user' . $i, $object);
        }

        for ($i=0; $i < 50; $i++) {
            $object = (new User())
                ->setLevel($levels[array_rand($levels)])
                ->setLocality($localities[array_rand($localities)])
                ->setEmail('user' . $i . '@user.fr')
                ->setUsername($faker->userName)
                ->setFirstname($faker->firstName())
                ->setLastname($faker->lastName())
                ->setStatus(TRUE)
                ->setRoles([])
                ->setPassword($pwd)
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LevelFixtures::class,
            BadgeFixtures::class,
            LocalityFixtures::class,
        ];
    }
}
