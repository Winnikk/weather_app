<?php

namespace App\Controller;

use App\Service\WeatherUtils;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class WeatherApiController extends AbstractController
{
    /**
     * @param Request $request
     * @param WeatherUtils $weatherUtils
     * @return Response
     * @Route("/api/weather.json", name="api_weather_json")
     */
    public function weatherActionJSON(Request $request, WeatherUtils $weatherUtils): Response
    {
        $locationMeasurements = $this->fetchWeather($request, $weatherUtils);

        $measurements = [];
        foreach($locationMeasurements["measurements"] as $measurement)
        {
            $measurements[] = [
                "id" => $measurement->getId(),
                "date" => $measurement->getDate(),
                "temperature" => $measurement->getTemperature()
            ];
        }

        $content = [
            "location" => [
                "id" => $locationMeasurements["location"]->getId(),
                "country" => $locationMeasurements["location"]->getCountry(),
                "city" => $locationMeasurements["location"]->getCity(),
                "latitude" => $locationMeasurements["location"]->getLatitude(),
                "longitude" => $locationMeasurements["location"]->getLongtitude()
            ],
            "measurements" => $measurements
        ];

        $response = new Response();
        $response->setContent(json_encode($content));
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param Request $request
     * @param WeatherUtils $weatherUtils
     * @return string
     * @Route("/api/weather.csv", name="api_weather_csv")
     */
    public function weatherActionCSV(Request $request, WeatherUtils $weatherUtils): Response
    {
        $locationMeasurements = $this->fetchWeather($request, $weatherUtils);

        $measurements = [];
        foreach($locationMeasurements["measurements"] as $measurement)
        {
            $measurements[] = implode(",",
                array(
                    $measurement->getId(),
                    $measurement->getDate()->format('d/m/Y'),
                    $measurement->getTemperature()
                )
            );
        }

        $location = array(
            $locationMeasurements["location"]->getId(),
            $locationMeasurements["location"]->getCountry(),
            $locationMeasurements["location"]->getCity(),
            $locationMeasurements["location"]->getLatitude(),
            $locationMeasurements["location"]->getLongtitude()
        );

        $location = implode(",", $location);
        $measurements = implode(",", $measurements);

        $content = array(
            $location,
            $measurements
        );

        $response = new Response();
        $response->setContent(implode(',', $content));
        $response->headers->set('Content-Type', 'text/csv');

        return $response;
    }

    public function weatherActionTwigJson(Request $request, WeatherUtils $weatherUtils)
    {
        $locationMeasurements = $this->fetchWeather($request, $weatherUtils);

        return $this->render('weather_api/weather.json.twig', [
            'location' => $locationMeasurements["location"],
            'measurements' => $locationMeasurements["measurements"],
        ]);
    }

    public function weatherActionTwigCsv(Request $request, WeatherUtils $weatherUtils)
    {
        $locationMeasurements = $this->fetchWeather($request, $weatherUtils);

        return $this->render('weather_api/weather.csv.twig', [
            'location' => $locationMeasurements["location"],
            'measurements' => $locationMeasurements["measurements"],
        ]);
    }

    private function decodePayload(Request $request)
    {
        $payload = $request->getContent();

        return json_decode($payload, true);
    }

    private function fetchWeather($request, WeatherUtils $weatherUtils): array
    {
        $payload = $this->decodePayload($request);

        $country = $payload['country'];
        $city = $payload['city'];

        if (!$country OR !$city) {
            throw new BadRequestException("Bad request format");
        }

        return $weatherUtils->getWeatherForCountryAndCity($country, $city);
    }
}
