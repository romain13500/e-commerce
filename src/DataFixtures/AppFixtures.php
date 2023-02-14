<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    protected $slugger;

    public function __construct(SluggerInterface $slugger)
    {
        $this->slugger = $slugger;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR');
        $faker->addProvider( new \Liior\Faker\Prices($faker));
        $faker->addProvider(new \Bezhanov\Faker\Provider\Commerce($faker));

        for ($p=0; $p < 100 ; $p++) { 
            $product = new Product;
            $product->setName($faker->productName)
                ->setPrice($faker->price(4000, 2000))
                ->setSlug(strtolower($this->slugger->slug($product->getName())));

            $manager->persist($product);
        }
        $manager->flush();
    }
}
