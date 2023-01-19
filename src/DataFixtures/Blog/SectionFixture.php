<?php

namespace App\DataFixtures\Blog;

use App\Entity\Blog\Section;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SectionFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $section = new Section(
            machineName: 'administ',
            name: 'Администрирование',
            enabled: true,
            hidden: false
        );

        $manager->persist($section);
    }
}
