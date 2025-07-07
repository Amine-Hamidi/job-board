<?php

namespace App\DataFixtures;

use App\Entity\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Bundle\FixturesBundle\FixtureGroupInterface;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture implements FixtureGroupInterface
{
    public function load(ObjectManager $manager): void
    {
        $labels = ['PHP', 'Symfony', 'React', 'JavaScript', 'HTML', 'CSS', 'DevOps', 'Docker', 'MySQL', 'Laravel'];

         foreach ($labels as $label) {
            $tag = new Tag();
            $tag->setLabel($label);
            $manager->persist($tag);
        }

        $manager->flush();
    }

    public static function getGroups(): array
    {
        return ['Default', 'TagFixtures'];
    }
    
}
