<?php

namespace App\Helpers;

class Messages
{
    protected static $messages =
        [
        'validation_messages' => [
            'required' => 'REQUEST_PARAMETER_MISSING',
            'string' => 'REQUEST_PARAMETER_INVALID_DATA_TYPE',
            'integer' => 'REQUEST_PARAMETER_INVALID_DATA_TYPE',
            'array' => 'REQUEST_PARAMETER_INVALID_DATA_TYPE',
            'size' => 'REQUEST_PARAMETER_INVALID_SIZE',
            'latitude' => 'INVALID_LONGITUDE',
            'longitude' => 'INVALID_LATITUDE',
            'min' => 'NUMBER_MUST_BE_MINIMUM_1',
            'max' => 'INVALID_NUMBER_MUST_BE_MAXIMUM_1000',
            'different' => 'VALUE_MUST_NOT_BE_SAME',
            'in' => 'INVALID_STATUS',
        ],
        'orders' => [
            'invalid_id' => 'INVALID_ORDER_ID',
            'success' => 'SUCCESS',
            'order_conflict' => 'ORDER_ALREADY_TAKEN',
            'id_not_exist' => 'ORDER_ID_NOT_EXIST',
        ],
        'errors' => [
            'bad' => 'BAD_REQUEST',
            'guzzle' => 'SOMETHING_WENT_WRONG_WITH_GUZZLEHTTP_CLIENT',

        ],
        'google_api' => [
            'key_error' => 'GOOGLE_MAP_API_KEY_IS_NOT_SET',
        ],
        '',
    ];

    /**
     * Provided translated message if key is provided, otherwise provided whole array of
     * key->translated_message pairs
     *
     * @param string|null $key
     *
     * @return array|string|null
     */
    public static function get($key = null)
    {
        $messageList = self::$messages;

        if (null === $key) {
            return $messageList;
        }
        return array_get($messageList, $key);
    }

    private $jsonResponseMessagesCode = [
        'HTTP_NOT_ACCEPTABLE' => 406,
        'HTTP_BAD_REQUEST' => 400,
        'HTTP_OK' => 200,
        'HTTP_CONFLICT' => 409,
        'HTTP_NOT_FOUND' => 404,
        'HTTP_UNPROCESSABLE_ENTITY' => 422,
        'HTTP_METHOD_NOT_ALLOWED' => 405,
        'HTTP_SERVICE_UNAVAILABLE' => 503,
    ];
}
