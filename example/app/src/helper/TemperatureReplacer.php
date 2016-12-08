<?php

namespace Example\Helper;

use Easycore\Crapi\Replacer;
use DateTime;
use DateTimeZone;

class TemperatureReplacer implements Replacer
{
    const LOCATIONS = [
        1 => "balcony",
        2 => "garden"
    ];

    /**
     * @param $key string
     * @return array|object|string
     */
    function replaceKey($key)
    {
        switch ($key) {
            case "sensors":
                return $this->getSensors();
            case "last_updated":
                return $this->getLastUpdated();
            default:
                return null;
        }
    }

    /**
     * Generates two example temperatures.
     *
     * @return array
     */
    public function getSensors()
    {
        $tm1 = floor((time() - 96) / 150) * 150 + 96;
        $tm2 = $tm1 - 18 * 3600 - 5 * 60 - 20;

        $tempData1 = $this->getTemperatureSensorData(1, $tm1, $tm1);
        $tempData2 = $this->getTemperatureSensorData(2, $tm1, $tm2);
        $humidity1 = $this->getHumiditySensorData(1, $tm1);
        $humidity2 = $this->getHumiditySensorData(1, $tm2);

        switch (2) {
            case 0: // by sensor groups
                return [
                    $this->sensorGroupWrapper(1, self::LOCATIONS[1], [$tempData1, $humidity1]),
                    $this->sensorGroupWrapper(2, self::LOCATIONS[2], [$tempData2, $humidity2])
                ];
            case 1: // by type
                return [
                    $this->sensorGroupWrapper(1, "temperature", [$tempData1, $tempData2]),
                    $this->sensorGroupWrapper(2, "humidity", [$humidity1, $humidity2])
                ];
            case 2:
            default:
                return [ $tempData1, $tempData2 ];
        }
    }

    /**
     * Returns time differences from initial measuring time.
     *
     * @return array
     */
    private function getTimeDiffIntervals() {
        return range(0, -10 * 60, -5 * 60);
    }

    /**
     * Returns data read from thermal sensor.
     *
     * @param $idx int index of temperature reading
     * @param $displayTime int unixtime used for displaying reading timestamp
     * @param $tempTime int unixtime used for calculating temperature
     *
     * @return array
     */
    private function getTemperatureSensorData($idx, $displayTime, $tempTime) {
        $readings = [];
        foreach ($this->getTimeDiffIntervals() as $currentDiff) {
            $readings[] = [
                "timestamp" => $this->unixToString($displayTime + $currentDiff),
                "value" => $this->randomError(0.05, function () use($tempTime, $currentDiff) {
                    return $this->getTemperatureForTime($tempTime + $currentDiff);
                })
            ];
        }

        return [
            "id" => sprintf("thermo%d", $idx),
            "type" => "temperature",
            "unit" => "celsius",
            "sub-type" => "outdoors",
            "location" => self::LOCATIONS[$idx],
            "readings" => $readings
        ];
    }

    /**
     * Returns data read from humidity sensor.
     *
     * @param $idx int index of humidity reading
     * @param $displayTime int unixtime used for displaying reading timestamp
     *
     * @return array
     */
    private function getHumiditySensorData($idx, $displayTime) {
        $readings = [];
        foreach ($this->getTimeDiffIntervals() as $currentDiff) {
            $readings[] = [
                "timestamp" => $this->unixToString($displayTime + $currentDiff),
                "value" => $this->randomError(0.05, function () {
                    return rand(7000, 8000) / 100;
                })
            ];
        }

        return [
            "id" => sprintf("humidity_detector_%d", $idx),
            "type" => "humidity",
            "unit" => "percent",
            "sub-type" => "outdoors",
            "location" => self::LOCATIONS[$idx],
            "readings" => $readings
        ];
    }

    /**
     * Wraps sensor data into sensor_group bundle.
     *
     * @param $idx int index of sensor_group bundle
     * @param $type string type of sensor_group
     * @param $sensors array data to wrap
     *
     * @return array
     */
    private function sensorGroupWrapper($idx, $type, $sensors) {
        return [
            "id" => sprintf("sensor_group_%d", $idx),
            "type" => $type,
            "sensors" => $sensors
        ];
    }

    /**
     * Returns time when data was last updated.
     *
     * @return string
     */
    public function getLastUpdated()
    {
        $tm = floor((time() - 20 * 60 - 36) / 3600) * 3600 + (20 * 60 + 36);
        return $this->unixToString($tm);
    }

    /**
     * Returns human readable time format from unix.
     *
     * @param $unix int unixtime to format
     *
     * @return string
     */
    private function unixToString($unix)
    {
        $dt = new DateTime("@{$unix}");
        $dt->setTimezone(new DateTimeZone("Europe/Prague"));
        return $dt->format(DATE_RFC3339);
    }

    /**
     * @param $chance float chance from 0.0 to 1.0
     * @param $callable callable method to call when no error occurs
     * @return null|mixed
     */
    private function randomError($chance, $callable)
    {
        if (rand(0, 100000) / 100000 < $chance) {
            return null;
        }

        return $callable();
    }

    /**
     * Returns generated temperature for $time.
     *
     * @param $time int unix time
     * @return float
     */
    private function getTemperatureForTime($time)
    {
        $dayInYear = date('z', $time) + 1;
        $totalDaysInYear = (date('Y', $time) % 4 == 0) ? 366 : 365;
        $yearProgress = $dayInYear / $totalDaysInYear;
        $p1 = sin(2 * pi() * $yearProgress);

        $dayProgress = $time % 86400 / 86400;
        $p2 = sin(2 * pi() * $dayProgress) / 10;

        $daysFromUnixStart = floor($time / 86400);
        $coefficient = $daysFromUnixStart / 24;
        $p3 = sin(10 * pi() * $coefficient) / 20;

        return $p1 + $p2 + $p3;
    }
}