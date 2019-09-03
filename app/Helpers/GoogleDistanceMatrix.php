<?php

namespace App\Helpers;
use GuzzleHttp\Exception\RequestException;

class GoogleDistanceMatrix {

    const GOOGLE_MAP_URL = "https://maps.googleapis.com/maps/api/distancematrix/OUTPUT_FORMAT?units=UNIT&origins=ORIGIN_VALUES&destination=DESTINATION_VALUES&key=API_KEY";
    
     /**
     * Calculates the distance in meter with the help of Google Ditance Matrix API, between
     * Origin co-ordinates and destination co-ordinates
     * @param array $origin, Origin set of Latitude and Longitude
     * @param array $destination, Origin set of Latitude and Longitude
     * @return string|float Distance between points in [m] (same as earthRadius)
     */
      public static function getMapDistance(array $origin, array $destination)
      {
          $origin = implode(",", $origin);
          $destination = implode(",", $destination);
          // $coordinates = $origin . "|" . $destination;
          $searchKeys = ['OUTPUT_FORMAT', 'UNIT', 'ORIGIN_VALUES', 'DESTINATION_VALUES', 'API_KEY'];
          if(env('GOOGLE_MAP_KEY') == "")
          {
              abort(422, 'Google Map API_KEY is not set.');
          }
          $replacements = ['json', 'metric', $origin, $destination, env('GOOGLE_MAP_KEY')];
          $url = str_replace($searchKeys, $replacements, self::GOOGLE_MAP_URL);
  
          try {
              $client = new \GuzzleHttp\Client();
              $response = $client->request('GET', $url);
              $body = $response->getBody()->getContents();
              $content = json_decode($body);
              $status = $content->status;
  
              if ( $status !== 'OK' )
              {
                  abort(404, 'Something went wrong with Google Map API.');
              }
              return
              $distance = $content['rows'][0]['elements'][0]['distance']['value'];   // in meters
            //   $distance = $content['rows'][0]['elements'][0]['distance']['text'];    // in Km
          } catch (RequestException $ex) {
              abort(404, 'Something went wrong with Google Map API.');
          }
  
      }

}