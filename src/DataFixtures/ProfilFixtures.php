<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Profil;

class ProfilFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $profil = new Profil();
        $profil->setLibelle('Prof');
        $manager->persist($profil);

        //$manager->flush();
    }
}
