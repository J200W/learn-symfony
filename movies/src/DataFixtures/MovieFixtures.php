<?php

namespace App\DataFixtures;

use App\Entity\Movie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class MovieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $movie = new Movie();
        $movie->setTitle("Avengers: Endgame");
        $movie->setDescription("The Avengers take a final stand against Thanos in Marvel Studios' conclusion to 22 films, 'Avengers: Endgame.'");
        $movie->setReleaseYear(2019);
        $movie->setImagePath("https://lumiere-a.akamaihd.net/v1/images/p_avengersendgame_19751_e14a0104.jpeg?region=0%2C0%2C540%2C810");
        
        // We use the references we created in ActorFixtures.php
        $movie->addActor($this->getReference("actor_1"));
        $movie->addActor($this->getReference("actor_2"));
        $manager->persist($movie);
        
        $movie2 = new Movie();
        $movie2->setTitle("The Dark Knight");
        $movie2->setDescription("The Dark Knight must accept one of the greatest psychological and physical tests of his ability to fight injustice.");
        $movie2->setReleaseYear(2018);
        $movie2->setImagePath("https://fr.web.img2.acsta.net/medias/nmedia/18/63/97/89/18949761.jpg");
        
        // We use the references we created in ActorFixtures.php
        $movie2->addActor($this->getReference("actor_3"));
        $movie2->addActor($this->getReference("actor_4"));
        $manager->persist($movie2);

        $manager->flush();
    }
}