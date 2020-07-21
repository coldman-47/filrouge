<?php

namespace App\DataFixtures;

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
        $user->setUsername('ibou');
        $user->setPassword(
            $this->passwordEncoder->encodePassword(
                $user,
                'thapass'
            )
        );
        $manager->persist($user);
        $user->setPrenom('Ibrahima');
        $user->setNom('GUEYE');
        $user->setEmail('ibou@odc.sn');

        $manager->flush();
    }
}
