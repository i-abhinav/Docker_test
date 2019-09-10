<?php

namespace App\Helpers;

use Faker\Factory as Faker;

class FakeData
{

    public static function invalidLongitude(): array
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [(string) $faker->latitude, $faker->email],
            'destination' => [(string) $faker->latitude, (string) $faker->longitude],
        ];
    }

    public static function emptyLongitude(): array
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [(string) $faker->latitude, ''],
            'destination' => [(string) $faker->latitude, ''],
        ];
    }

    public static function invalidLatitude(): array
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [$faker->name, (string) $faker->longitude],
            'destination' => [(string) $faker->latitude, $faker->longitude],
        ];
    }

    public static function emptyLatitude(): array
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => ['', (string) $faker->longitude],
            'destination' => ['', $faker->longitude],
        ];
    }

    public static function invalidLatitudeLongitude(): array
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [$faker->name, (string) $faker->longitude],
            'destination' => [(string) $faker->latitude, $faker->email],
        ];
    }

    public static function invalidFormatLatitude(): array
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [(string) $faker->latitude, (string) $faker->longitude],
            'destination' => [$faker->latitude, $faker->longitude],
        ];
    }

    public static function invalidFormatLongitude(): array
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [(string) $faker->latitude, (string) $faker->longitude],
            'destination' => [(string) $faker->latitude, $faker->longitude],
        ];
    }

    public static function validCoordinates(): array
    {
        return
        $validData = [
            'origin' => ["28.644800", "77.308601"], // Delhi
            'destination' => ["19.076090", "72.877426"], //Mumbai
        ];
    }

    public static function invalidKeys(): array
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origi23n' => [(string) $faker->latitude, (string) $faker->longitude],
            'destinatioon' => [(string) $faker->latitude, (string) $faker->longitude],
        ];
    }

    public static function invalidNumberOfParams(): array
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [(string) $faker->latitude, (string) $faker->longitude, (string) $faker->longitude],
            'destination' => [(string) $faker->latitude, (string) $faker->longitude],
        ];
    }

    public static function sameCoordiates(): array
    {
        return
        $validData = [
            'origin' => ["28.644800", "77.308601"], // Delhi
            'destination' => ["28.644800", "77.308601"], //Delhi
        ];
    }

}
