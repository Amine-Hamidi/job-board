<?php

namespace App\DataFixtures;

use App\Entity\Candidate;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CandidateFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $users = $manager->getRepository(User::class)->findAll();

        foreach ($users as $user) {
            if (in_array('ROLE_CANDIDATE', $user->getRoles())) {
                $candidate = new Candidate();
                $candidate->setUser($user);
                $candidate->setCv('cv_' . $faker->uuid() . '.pdf');
                $candidate->setSkills($faker->words(3, true));
                $candidate->setExperience($faker->sentence());
                $candidate->setEducationLevel($faker->randomElement(['Bac+2', 'Licence', 'Master', 'Doctorat']));
                $candidate->setAvailability('Immédiate');


                $manager->persist($candidate);
            }
        }

        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}
