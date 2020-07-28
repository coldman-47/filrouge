<?php

namespace App\DataFixtures;

use App\Entity\User;
use App\Entity\Profil;
use App\Entity\ProfilSortie;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProfilSortieFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {

        $profil = $manager->getRepository(Profil::class)->findOneBy(['libelle' => 'APPRENANT']);
        $apprenants = $manager->getRepository(User::class)->findBy(['profil' => $profil]);

        foreach ($apprenants as $apprenant) {
            $id = rand(1, 2);
            $ps = $manager->getRepository(ProfilSortie::class)->find($id);
            $apprenant->addProfilSorty($ps);
            $ps->addProfilApprenant($apprenant);

            $manager->persist($ps);
            $manager->flush();
        }
    }
}
