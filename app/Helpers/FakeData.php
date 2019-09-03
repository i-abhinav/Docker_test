<?php

namespace App\Helpers;

use Faker\Factory as Faker;

class FakeData
{


    public static function invalidLongitude()
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [(string)$faker->latitude, $faker->email],
            'destination' => [(string)$faker->latitude, (string)$faker->longitude],
        ];
    }

    public static function emptyLongitude()
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [(string)$faker->latitude, ''],
            'destination' => [(string)$faker->latitude, ''],
        ];
    }

    public static function invalidLatitude()
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [$faker->name, (string)$faker->longitude],
            'destination' => [(string)$faker->latitude, $faker->longitude],
        ];
    }

    public static function emptyLatitude()
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => ['', (string)$faker->longitude],
            'destination' => ['', $faker->longitude],
        ];
    }

    public static function invalidLatitudeLongitude()
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [$faker->name, (string)$faker->longitude],
            'destination' => [(string)$faker->latitude, $faker->email],
        ];
    }

    public static function invalidFormatLatitude()
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [(string)$faker->latitude, (string)$faker->longitude],
            'destination' => [$faker->latitude, $faker->longitude],
        ];
    }

    public static function invalidFormatLongitude()
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [(string)$faker->latitude, (string)$faker->longitude],
            'destination' => [(string)$faker->latitude, $faker->longitude],
        ];
    }

    public static function validCoordinates()
    {
        $faker = Faker::create();
        return
        $validData = [
            'origin' => [(string)$faker->latitude, (string)$faker->longitude],
            'destination' => [(string)$faker->latitude, (string)$faker->longitude],
        ];
    }

    public static function invalidKeys()
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origi23n' => [(string)$faker->latitude, (string)$faker->longitude],
            'destinatioon' => [(string)$faker->latitude, (string)$faker->longitude],
        ];
    }

    public static function invalidNumberOfParams()
    {
        $faker = Faker::create();
        return
        $invalidData = [
            'origin' => [(string)$faker->latitude, (string)$faker->longitude, (string)$faker->longitude],
            'destination' => [(string)$faker->latitude, (string)$faker->longitude],
        ];
    }

}