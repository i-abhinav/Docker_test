<?php

use App\Helpers\Haversine;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        foreach (range(1, 10) as $index) {
            $originLat = $faker->latitude();
            $originLong = $faker->latitude();
            $destinationLat = $faker->longitude();
            $destinationLong = $faker->longitude();
            $distance = Haversine::getDistance($originLat, $originLong, $destinationLat, $destinationLong);
            DB::table('orders')->insert([
                'origin_lat' => $originLat,
                'origin_lng' => $originLong,
                'destination_lat' => $destinationLat,
                'destination_lng' => $destinationLong,
                'distance' => $distance,
                'status' => 'UNASSIGNED',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
        }

        DB::table('orders')->where('id', 3)->update(['status' => 'ASSIGNED']);
    }
}
