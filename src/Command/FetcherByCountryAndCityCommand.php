<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Service\WeatherUtils;

#[AsCommand(
    name: 'fetcher:by:country:and:city',
    description: 'Fetch measurement by its country code and city name',
)]
class FetcherByCountryAndCityCommand extends Command
{
    private WeatherUtils $weatherUtils;

    protected function configure(): void
    {
        $this
            ->addArgument('countryCode', InputArgument::OPTIONAL, 'Country Code')
            ->addArgument('cityName', InputArgument::OPTIONAL, 'City Name')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    public function __construct(WeatherUtils $weatherUtils)
    {
        $this->weatherUtils = $weatherUtils;

        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $countryCode = $input->getArgument('countryCode');
        $cityName = $input->getArgument('cityName');

        if ($countryCode) {
            $io->note(sprintf('You passed a country code: %s', $countryCode));
        }

        if ($cityName) {
            $io->note(sprintf('You passed a city name: %s', $cityName));
        }

        $serviceResult = $this->weatherUtils->getWeatherForCountryAndCity(strval($countryCode), strval($cityName));
        $measurements = [];
        foreach($serviceResult["measurements"] as $measurement) {
            $measurements[] = [
                "date" => $measurement->getDate(),
                "temperature" => $measurement->getTemperature()
            ];
        }
        $result = [
            "country" => $serviceResult["location"]->getCountry(),
            "city" => $serviceResult["location"]->getCity(),
            "measurement" => $measurements
        ];

        $io->success(json_encode($result));

        return Command::SUCCESS;
    }
}
