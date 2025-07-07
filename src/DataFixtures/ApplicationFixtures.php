<?php

namespace App\DataFixtures;

use App\Entity\Application;
use App\Entity\Candidate;
use App\Entity\Offer;
use Doctrine\Bundle\FixturesBundle\Fixture;
use App\Enum\ApplicationStatus;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;


class ApplicationFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $candidates = $manager->getRepository(Candidate::class)->findAll();
        $offers = $manager->getRepository(Offer::class)->findAll();

        if (empty($candidates) || empty($offers)) {
            dump('Aucun candidat ou offre en base');
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            $application = new Application();
            $application->setMessage($faker->paragraph());
            $application->setApplicationDate($faker->dateTimeBetween('-2 months', 'now'));
            $application->setStatus($faker->randomElement(ApplicationStatus::cases()));
            $application->setCandidate($faker->randomElement($candidates));
            $application->setOffer($faker->randomElement($offers));

            $manager->persist($application);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CandidateFixtures::class,
            OfferFixtures::class,
        ];
    }
}
