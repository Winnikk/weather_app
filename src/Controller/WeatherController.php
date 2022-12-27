<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Service\WeatherUtils;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Location;
use App\Repository\MeasurementRepository;
use App\Repository\LocationRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class WeatherController extends AbstractController
{
    /**
     * @IsGranted ("ROLE_WEATHER_SHOW", message="No access!")
     */
    public function cityAction(string $country, string $city, WeatherUtils $weatherUtils ): Response
    {
        $serviceResult = $weatherUtils->getWeatherForCountryAndCity($country, $city);

        return $this->render('weather/city.html.twig', [
            'location' => $serviceResult["location"],
            'measurements' => $serviceResult["measurements"],
        ]);
    }
}
