<?php

namespace App\DataFixtures;

use App\Entity\Apprenant;
use App\Entity\ProfilSortie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilSortieFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $profilsorties = ["Designer", "Full Stack", "CMS"];
        // foreach ($profilsorties as $val) {
        //     $profilsortie = new ProfilSortie();
        //     $profilsortie->setLibelle($val);
        //     $manager->persist($profilsortie);
        //     $manager->flush();
        // }
        $apprenants = $manager->getRepository(Apprenant::class)->findAll();

        foreach ($apprenants as $apprenant) {
            $id = rand(7, 9);
            $ps = $manager->getRepository(ProfilSortie::class)->find($id);
            $apprenant->addProfilSorty($ps);
            $ps->addApprenant($apprenant);

            $manager->persist($ps);
            $manager->flush();
        }
    }
}
