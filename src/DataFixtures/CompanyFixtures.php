<?php

namespace App\DataFixtures;

use App\Entity\Company;
use App\Entity\User;
use App\Entity\Sector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CompanyFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $manager->getRepository(User::class)->findAll();
        $sectors = $manager->getRepository(Sector::class)->findAll();

        foreach ($users as $user) {
            if (in_array('ROLE_COMPANY', $user->getRoles())) {
                $company = new Company();
                $company->setName($faker->company());
                $company->setDescription($faker->paragraph());
                $company->setUser($user);


                if (!empty($sectors)) {
                    $company->setSector($faker->randomElement($sectors));
                }

                $manager->persist($company);
            }
        }

        $manager->flush();
    }

     public function getDependencies(): array
    {
        return [
            UserFixtures::class,
            SectorFixtures::class,
        ];
    }
}
