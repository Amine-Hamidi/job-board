<?php

namespace App\DataFixtures;
use App\Enum\OfferStatus;
use App\Entity\Company;
use App\Entity\Offer;
use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class OfferFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        // Récupère les entreprises existantes
        $companies = $manager->getRepository(Company::class)->findAll();
        $tags = $manager->getRepository(Tag::class)->findAll();

        if (empty($companies)) {
            dump("Aucune entreprise trouvée");
            return;
        }

        for ($i = 0; $i < 10; $i++) {
            $offer = new Offer();
            $offer->setTitle($faker->jobTitle());
            $offer->setDescription($faker->paragraph(3));
            $offer->setContractType($faker->randomElement(['CDI', 'CDD', 'Stage', 'Freelance']));
            $offer->setSalary($faker->numberBetween(25000, 60000));
            $offer->setPublicationDate($faker->dateTimeBetween('-3 months', 'now'));
            $offer->setStatus(OfferStatus::PUBLISHED);

            // Lier à une entreprise existante
            $offer->setCompany($faker->randomElement($companies));

            // Ajouter des tags si disponibles
            if (!empty($tags)) {
                foreach ($faker->randomElements($tags, rand(1, 3)) as $tag) {
                    $offer->addTag($tag);
                }
            }

            $manager->persist($offer);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CompanyFixtures::class,
            TagFixtures::class,
        ];
    }

}
