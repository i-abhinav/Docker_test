<?php

namespace App\Http\Validations;

use App\Helpers\Messages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderValidator
{

    protected static $orderCreateRules =
        [
        'origin' => 'bail|required|array|size:2',
        'origin.0' => "required|string|latitude", //lattitude
        'origin.1' => 'required|string|longitude', //longitude
        'destination' => 'bail|required|array|size:2||different:origin',
        'destination.0' => 'required|string|latitude', //lattitude
        'destination.1' => 'required|string|longitude', //longitude
    ];

    protected static $orderTakeRules = [
        'status' => 'bail|required|string|in:TAKEN',
        'order_id' => 'bail|required|integer|min:1',
    ];

    protected static $orderListRules =
        [
        'limit' => 'bail|required|integer|min:1|max:1000',
        'page' => 'bail|required|integer|min:1',
    ];

    public static function storeRequestValidate(Request $request): array
    {
        $validator = Validator::make($request->all(), self::$orderCreateRules, Messages::get('validation_messages'));

        if ($validator->fails()) {
            return ['status' => false, 'errors' => $validator->errors()->first()];
        }
        return ['status' => true, 'errors' => $validator->errors()->first()];
    }

    public static function listRequestValidate(Request $request): array
    {
        $validator = Validator::make($request->all(), self::$orderListRules, Messages::get('validation_messages'));
        if ($validator->fails()) {
            return ['status' => false, 'errors' => $validator->errors()->first()];
        }
        return ['status' => true, 'errors' => $validator->errors()->first()];
    }

    public static function takeRequestValidate(Request $request): array
    {
        $validator = Validator::make($request->all(), self::$orderTakeRules, Messages::get('validation_messages'));
        if ($validator->fails()) {
            return ['status' => false, 'errors' => $validator->errors()->first()];
        }
        return ['status' => true, 'errors' => $validator->errors()->first()];
    }

}
