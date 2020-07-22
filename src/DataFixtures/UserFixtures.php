<?php

namespace App\DataFixtures;

use App\Entity\Profil;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixtures extends Fixture
{
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('jeanne');
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'thapass'
            )
        );
        $user->setPrenom('Jeannette');
        $user->setNom('PREIRA');
        $user->setEmail('jeanne@odc.sn');
        $profil = $manager->getRepository(Profil::class)->findOneBy(['id' => 3]);
        $user->setProfil($profil);

        $manager->persist($user);
        $manager->flush();
    }
}
