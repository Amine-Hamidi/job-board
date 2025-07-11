<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements FixtureGroupInterface
{
    public function __construct(private UserPasswordHasherInterface $passwordHasher) {}

    public function load(ObjectManager $manager): void
    {
        $userCandidate = new User();
        $userCandidate->setEmail('amine060692@gmail.com');
        $userCandidate->setPassword($this->passwordHasher->hashPassword($userCandidate, '123456'));
        $userCandidate->setRoles(['ROLE_USER', 'ROLE_CANDIDATE']);
        $manager->persist($userCandidate);

        $userCompany = new User();
        $userCompany->setEmail('recruteur@example.com');
        $userCompany->setPassword($this->passwordHasher->hashPassword($userCompany, '78945'));
        $userCompany->setRoles(['ROLE_USER', 'ROLE_COMPANY']);
        $manager->persist($userCompany);

        $admin = new User();
        $admin->setEmail('admin@example.com');
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'admin123'));
        $admin->setRoles(['ROLE_ADMIN']);
        $manager->persist($admin);

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['Default', 'UserFixtures'];
    }
}
