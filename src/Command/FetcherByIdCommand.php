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
    name: 'fetcher:by:id',
    description: 'Fetch measurement by its ID',
)]
class FetcherByIdCommand extends Command
{
    private WeatherUtils $weatherUtils;

    protected function configure(): void
    {
        $this
            ->addArgument('measurementID', InputArgument::OPTIONAL, 'Measurement ID');
    }

    public function __construct(WeatherUtils $weatherUtils)
    {
        $this->weatherUtils = $weatherUtils;
        
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $measurementID = $input->getArgument('measurementID');

        if ($measurementID) {
            $io->note(sprintf('You passed an argument: %s', $measurementID));
        }
        $measurements = $this->weatherUtils->getWeatherByID($measurementID);
        $result = [];
        foreach($measurements as $measurement) {
            $result = [
                "date" => $measurement->getDate(),
                "temperature" => $measurement->getTemperature()
            ];
        }

        $io->success(json_encode($result));

        return Command::SUCCESS;
    }
}
