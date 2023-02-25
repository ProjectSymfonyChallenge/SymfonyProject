<?php

namespace App\DataFixtures;

use App\Entity\Locality;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class LocalityFixtures extends Fixture
{
    const API_URL = "https://geo.api.gouv.fr/";
    public function load(ObjectManager $manager): void
    {
        $url = self::API_URL."regions";
        $json = file_get_contents($url);
        $regions = json_decode($json, true);
        
        $localities = [];
        
        foreach ($regions as $region) {
            $regionUrl = self::API_URL . "regions/".$region["code"]."/departements";
            $json = file_get_contents($regionUrl);
            $regionDepartments = json_decode($json, true);
            
            foreach ($regionDepartments as $department) {
                $localities[$department["nom"]] = $region["nom"];
            }
        }

        foreach ($localities as $department => $region) {
            $locality = new Locality();
            $locality->setDepartment($department);
            $locality->setRegion($region);

            $manager->persist($locality);
        }

        $manager->flush();
    }
}
