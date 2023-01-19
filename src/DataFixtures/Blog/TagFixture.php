<?php

namespace App\DataFixtures\Blog;

use App\Entity\Blog\Tag;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TagFixture extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('ru_RU');
        for ($i = 0; $i < 100; $i++) {
            $tag = new Tag(name: $faker->word());
            $manager->persist($tag);
            $this->addReference(name: "tag{$i}", object: $tag);
        }

        $manager->flush();
    }
}
