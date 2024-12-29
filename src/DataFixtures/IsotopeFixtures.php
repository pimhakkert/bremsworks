<?php

namespace App\DataFixtures;

use App\Entity\Isotope;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class IsotopeFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        foreach(Isotope::ALL_SHORTHANDS_TO_NAMES as $shorthand => $name) {
            $isotope = new Isotope();
            $isotope->setShorthand($shorthand);
            $isotope->setName($name);
            $manager->persist($isotope);
        }

        $manager->flush();
    }
}
