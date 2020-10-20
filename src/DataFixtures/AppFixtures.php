<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('Fr-fr');

        for($i=1; $i<=30; $i++){

            $ad = new Ad();
            
            $title = $faker->sentence();
            $coverImage = "https://picsum.photos/640/480";
            $introduction = $faker->paragraph(2);
            $content = "<p>".join("</p><p>",$faker->paragraphs(5))."</p>";

            $ad ->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(30,100))
                ->setRooms(mt_rand(1,5))
            ;

            $manager->persist($ad);

            for($j=1;$j<=mt_rand(2,5);$j++)
            {
                $url = "https://picsum.photos/640/480";

                // on crée une nv instance de l'entité Image

                $image = new Image();
                $image->setUrl($url)
                      ->setCaption($faker->sentence())
                      ->setAd($ad)
                ;

                // on sauvegarde

                $manager->persist($image);
            }
        }

        $manager->flush();
    }
}
