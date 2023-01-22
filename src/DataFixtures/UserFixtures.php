<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // pwd = test
        $pwd = '$2y$13$0fWxLbIeHztLelSkgXLLAONeAD3e7MzH5ntmw4bJYtBQEsRinTNIO';

        $object = (new User())
            ->setEmail('user@user.fr')
            ->setRoles([])
            ->setPassword($pwd)
        ;
        $manager->persist($object);

        $object = (new User())
            ->setEmail('coach@user.fr')
            ->setRoles(['ROLE_COACH'])
            ->setPassword($pwd)
        ;
        $manager->persist($object);

        $object = (new User())
            ->setEmail('client@user.fr')
            ->setRoles(['ROLE_CLIENT'])
            ->setPassword($pwd)
        ;
        $manager->persist($object);

        $object = (new User())
            ->setEmail('admin@user.fr')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($pwd)
        ;
        $manager->persist($object);

        for ($i=0; $i<50; $i++) {
            $object = (new User())
                ->setEmail('user' . $i . '@user.fr')
                ->setRoles([])
                ->setPassword($pwd)
            ;
            $manager->persist($object);
        }

        $manager->flush();
    }
}
