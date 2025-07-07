<?php

namespace App\DataFixtures;

use App\Entity\Sector;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class SectorFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $sectors = [
            'Informatique',
            'Finance',
            'Santé',
            'Éducation',
            'Marketing',
            'Construction',
            'Transport',
            'Agroalimentaire',
        ];

        foreach ($sectors as $name) {
            $sector = new Sector();
            $sector->setName($name);
            $manager->persist($sector);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['Default', 'SectorFixtures'];
    }

}
