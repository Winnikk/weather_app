<?php

namespace App\Service;


use Symfony\Flex\Response;
use App\Repository\LocationRepository;
use App\Repository\MeasurementRepository;

class WeatherUtils
{
    private LocationRepository $locationRepository;
    private MeasurementRepository $measurementRepository;
    public function __construct(LocationRepository $locationRepository, MeasurementRepository $measurementRepository)
    {
        $this->locationRepository = $locationRepository;
        $this->measurementRepository = $measurementRepository;
    }

    public function getWeatherByID($id)
    {
        return $this->measurementRepository->findByID($id);
    }
    public function getWeatherForCountryAndCity($country, $city): array
    {
        $location = $this->locationRepository->findByCountryAndCity($country, $city);

        return [
            "location" => $location,
            "measurements" => $this->getWeatherForLocation($location)
        ];
    }

    public function getWeatherForLocation($location)
    {
        return $this->measurementRepository->findByLocation($location);
    }
}
