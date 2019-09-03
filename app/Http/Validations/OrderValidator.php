<?php

namespace App\Http\Validations;

use Illuminate\Http\Request;
use App\Rules\GeoCodes;
use Illuminate\Support\Facades\Validator;

class OrderValidator 
{

    protected static $orderCreateRules = [
                        'origin'=>'bail|required|array|size:2',
                        'origin.0'=>"required|string|latitude",  //lattitude
                        'origin.1'=>'required|string|longitude',  //longitude
                        'destination'=>'required|array|size:2',
                        'destination.0'=>'required|string|latitude',  //lattitude
                        'destination.1'=>'required|string|longitude'   //longitude
                    ];


    protected static $orderCreateRuleMessages = [
                        "origin.0.latitude" => "The :attribute is invalid Latitude.",
                        "origin.1.longitude" => "The :attribute is invalid Longitude.",
                        "destination.0.latitude" => "The :attribute is invalid Latitude.",
                        "destination.1.longitude" => "The :attribute is invalid Longitude."
                    ];

    protected static $orderTakeRules = ['status' => 'required|string|in:TAKEN'];


    protected static $orderListRules = [
                        'limit'=>'required|integer|min:1',
                        'page'=>'required|integer|min:1'
                    ];

    protected static $errors = [];

    public static function storeRequestValidate(Request $request)
    {
        $validator = Validator::make($request->all(), self::$orderCreateRules, self::$orderCreateRuleMessages);

        if ($validator->fails()) {
            return
            ['status'=>false, 'errors'=>$validator->errors()->first()];
        }
        return ['status'=>true, 'errors'=>$validator->errors()->first()];
    } 

    public static function listRequestValidate(Request $request)
    {
        $validator = Validator::make($request->all(), self::$orderListRules);
        if ($validator->fails()) {
            return
            ['status'=>false, 'errors'=>$validator->errors()->first()];
        }
        return ['status'=>true, 'errors'=>$validator->errors()->first()];
    }


    public static function takeRequestValidate(Request $request)
    {
        $validator = Validator::make($request->all(), self::$orderTakeRules);
        if ($validator->fails()) {
            return
            ['status'=>false, 'errors'=>$validator->errors()->first()];
        }
        return ['status'=>true, 'errors'=>$validator->errors()->first()];
    }
    


}


