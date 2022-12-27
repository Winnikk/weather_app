<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\User;
use App\Entity\Location;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setEmail("winnikkacper@gmail.com");
        $user->setRoles(["ROLE_ADMIN"]);
        $user->setPassword("$2y$13$69cnvXZffb2jVjoRixYE6.FLMqlARcV0aQNOwi.44rSIFpAaMesdq");
        $manager->persist($user);

        $location = new Location();
        $location->setCity("Szczecin");
        $location->setCountry("PL");
        $location->setLatitude(53.4285);
        $location->setLongtitude(14.5528);
        $manager->persist($location);

        $location = new Location();
        $location->setCity("Warszawa");
        $location->setCountry("PL");
        $location->setLatitude(52.2297);
        $location->setLongtitude(21.0122);
        $manager->persist($location);

        $location = new Location();
        $location->setCity("Krakow");
        $location->setCountry("PL");
        $location->setLatitude(50.0647);
        $location->setLongtitude(19.9450);
        $manager->persist($location);

        $manager->flush();
    }
}
