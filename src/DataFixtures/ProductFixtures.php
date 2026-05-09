<?php

namespace App\DataFixtures;

use App\Entity\Product;
use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Define some product categories
        $categories = ['Electronics', 'Clothing', 'Books', 'Home & Garden', 'Sports'];
        $categoryObjects = [];

        // Create category objects or get existing ones
        foreach ($categories as $categoryName) {
            $category = $manager->getRepository(Category::class)->findOneBy(['name' => $categoryName]);
            if (!$category) {
                $category = new Category();
                $category->setName($categoryName);
                $manager->persist($category);
            }
            $categoryObjects[] = $category;
        }
        $manager->flush();

        // Create 50 dummy products
        for ($i = 1; $i <= 50; $i++) {
            $product = new Product();
            $product->setName($faker->word . ' ' . $faker->unique()->numberBetween(100, 9999));
            $product->setDescription($faker->paragraph(3));
            $product->setPrice(round($faker->randomFloat(2, 9.99, 299.99), 2));
            $product->setImageFilename('product-' . $i . '.jpg');
            $product->setCategory($categoryObjects[array_rand($categoryObjects)]);

            $manager->persist($product);
        }

        $manager->flush();
    }
}
