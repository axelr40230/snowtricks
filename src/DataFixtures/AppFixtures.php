<?php

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Comment;
use App\Entity\Trick;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        for ($j = 1; $j <=3; $j++) {
            $category = new Category();
            $category->setTitle($faker->words(3, true))
                ->setDescription($faker->paragraphs(2, true));

            $manager->persist($category);


//            $categories = $manager->getRepository(Category::class)->findAll();


            for($i = 1; $i <= mt_rand(4, 6); $i++){
                $trick = new Trick();

                $content = '<p>'.join($faker->paragraphs(5), '</p><p>') .'</p>';

                $trick->setTitle($faker->sentence())
                    ->setContent($content)
                    ->setImage($faker->imageUrl())
                    ->setCreatedAt($faker->dateTimeBetween('-6 months'))
                    ->setModifyAt($faker->dateTimeBetween('-6 months'))
                ->setCategory($category);

                $manager->persist($trick);

                for($k = 1; $k <= mt_rand(4,10); $k++){
                    $comment = new Comment();
                    $content = '<p>'.join($faker->paragraphs(2), '</p><p>') .'</p>';
                    $days = (new \DateTime())->diff($trick->getCreatedAt())->days;
                    $comment->setAuthor($faker->name)
                        ->setContent($content)
                        ->setTrick($trick)
                        ->setCreatedAt($faker->dateTimeBetween('-' .$days.' days'));

                    $manager->persist($comment);


                }
            }

            $manager->flush();
        }




    }
}
