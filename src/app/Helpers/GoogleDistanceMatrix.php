<?php

namespace App\Helpers;

use App\Exceptions\GoogleMapAPIException;
use App\Helpers\Messages;
use Illuminate\Http\JsonResponse;
use GuzzleHttp\Exception\RequestException;

class GoogleDistanceMatrix
{

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
        $searchKeys = ['ORIGIN_ADDRESSES', 'DESTINATION_ADDRESSES', 'API_KEY'];
        if (env('GOOGLE_MAP_KEY') == "") {
            throw new GoogleMapAPIException(Messages::get('google_api.key_error'));
        }
        $replacements = [$origin, $destination, env('GOOGLE_MAP_KEY')];
        $url = str_replace($searchKeys, $replacements, env('GOOGLE_MAP_URL'));

        try {
            $client = new \GuzzleHttp\Client();
            $response = $client->request('GET', $url);
            $body = $response->getBody()->getContents();
            $content = json_decode($body, true);

            $externalStatus = self::_checkExternalStatus($content);

            $internalStatus = self::_checkInternalStatus($content);

            $distance = $content['rows'][0]['elements'][0]['distance']['value']; // in meters
            return $distance;
            //   $distance = $content['rows'][0]['elements'][0]['distance']['text'];    // in Km
        } catch (RequestException $ex) {
            abort(JsonResponse::HTTP_NOT_FOUND, Messages::get('errors.guzzle'));
        }

    }

/**
 *  Checking GOOGLE MAP API Response external status is OK or not
 * @param array, $content, Response Body
 * @return bool|Exception
 */
    private static function _checkExternalStatus(array $content): bool
    {
        $status = $content['status'];
        if ($status !== 'OK') {
            throw new GoogleMapAPIException("GOOGLE_MAP_API_ERROR_" . $status);
        }
        return true;
    }

/**
 *  Checking GOOGLE MAP API Response Internal status is OK or not
 * @param array, $content, Response Body
 * @return bool|Exception
 */
    private static function _checkInternalStatus(array $content): bool
    {
        $status = $content['rows'][0]['elements'][0]['status'];
        if ($status !== 'OK') {
            throw new GoogleMapAPIException("GOOGLE_MAP_API_ERROR_" . $status);
        }
        return true;
    }
}
