<?php

namespace App\DataFixtures;

use App\Entity\Ad;
use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('Fr-fr');
        $users = [];
        $genders = ['male','female']; 

        // Utilisateurs
        for($i=1;$i<=10;$i++)
        {
            $user = new User();
            $gender = $faker->randomElement($genders);

            $avatar = 'https://randomuser.me/api/portraits/';
            $avatarId = $faker->numberBetween(1,99).'.jpg';

            $avatar .= ($gender == 'male' ? 'men/' : 'women/') . $avatarId;

            $hash = $this->encoder->encodePassword($user,'password');

            
            $description = "<p>".join("</p><p>",$faker->paragraphs(5))."</p>";
            $user->setDescription($description)
                 ->setFirstname($faker->firstname)
                 ->setLastname($faker->lastname)
                 ->setEmail($faker->email)
                 ->setIntroduction($faker->sentence())
                 ->setPw($hash)
                 ->setAvatar($avatar)
            ;

            $manager->persist($user);
            $users[] = $user;
        }

        // ANNONCES

        for($i=1; $i<=30; $i++){

            $ad = new Ad();
            
            $title = $faker->sentence();
            $coverImage = "https://picsum.photos/640/480";
            $introduction = $faker->paragraph(2);
            $content = "<p>".join("</p><p>",$faker->paragraphs(5))."</p>";
            $user = $users[mt_rand(0,count($users)-1)];

            $ad ->setTitle($title)
                ->setCoverImage($coverImage)
                ->setIntroduction($introduction)
                ->setContent($content)
                ->setPrice(mt_rand(30,100))
                ->setRooms(mt_rand(1,5))
                ->setAuthor($user)
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