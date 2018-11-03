<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFixture extends Fixture
{

    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
         $this->passwordEncoder = $passwordEncoder;
    }
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setEmail("haddad_antoine@hotmail.com");
        $user->setPassword($this->passwordEncoder->encodePassword(
             $user,
             'passantoine.haddad123'
        ));
        $manager->persist($user);

        $userAdmin = new User('admin');
        $userAdmin->setEmail("admin@bookeria.com");
        $userAdmin->setPassword($this->passwordEncoder->encodePassword(
            $userAdmin,
            'passadmin123'
       ));
        $manager->persist($userAdmin);

        $manager->flush();
    }
}
