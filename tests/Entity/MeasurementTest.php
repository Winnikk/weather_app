<?php

namespace App\Tests\Entity;

use App\Entity\Location;
use App\Entity\Measurement;
use DateTimeInterface;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

class MeasurementTest extends TestCase
{
    /**
     * @dataProvider provideConvertTemperature
     */
    public function testTemperatureConversion($expectedResult, $input): void
    {
        $measurement = new Measurement();
        $measurement->setTemperature($input);

        $this->assertEquals($expectedResult, $measurement->convertToFahrenheit());
    }

    public function provideConvertTemperature()
    {
        return [
            [32, 0],
            [5, -15],
            [89.6, 32],
            [-13, -25],
            [140, 60],
            [76.1, 24.5]
        ];
    }
}
