<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Models\Order;
use App\Helpers\Haversine;
use App\Http\Validations\OrderValidation;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\RequestException;


/**  
 *     @OA\Info(     
 *      title="MyOrder API", 
 *      version="1.0",
 *      description="MyOrder API",
 *      contact = {
 *              "name": "ABhinav Gupta",
 *              "email": "abhinav.gupta02@nagarro.com"
 *          }
 *      ), 
 * ) 
 */
class OrderController extends Controller
{


/**
 * @var int, Default Order-List Limit
 */
    protected $defaultLimit = 3;

/**
 * @var int, Default Order-List Page
 */
    protected $defaultPage = 1;


    /**
     * Retrieve the user for the given ID.
     *
     * @param  int  $id
     * @return Response
     */
    public function showOld($id)
    {
        return Order::findOrFail($id);
    }
    
     
/**
 * Create a new Order
 * A new Order is created when get a two set Array of Co-ordinates. 
 * Request Origin Latitude-Longitude and Destination Latitude-Longitude in strinf format
 *
 * @param  Request $request
 * @return Response, json
 */ 

/**
 * @OA\Post(path="/orders",
 *   tags={"Create New Order"},
 *   summary="Place an order",
 *   description="Create a new Order after valid request of Geo-coordinates",
 *   operationId="placeOrder",
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="origin",
 *                     type="array",
 *                      @OA\Items(type="string"),
 *                 ),
 *                 @OA\Property(
 *                     property="destination",
 *                     type="array",
*                      @OA\Items(type="string"),
 *                 ),
 *                 example={"origin": {"10", "80"}, "destination": {"83.232", "145.3487"}}
 *             )
 *         )
 *     ),
 * 
 *   @OA\Response(
 *     response=201,
 *     description="successful operation",
 *     @OA\Schema(ref="#/components/schemas/Order")
 *   ),
 *   @OA\Response(response=400, description="Bad Request"),
 *   @OA\Response(response=422, description="Invalid Parameters")
 * 
 * )
 */

    public function store(Request $request)
    {
        if(empty($request->all()))
        {
            return response()->json(['error'=>'Bad request'], 400);
        }
        
        $resp = OrderValidation::storeRequestValidate($request);
        if(!$resp['status']) {
            return
            response()->json(['error'=>$resp['errors']], 422);
        }

        $origin = $request->get('origin');
        $destination = $request->get('destination');
       
        try {
            $distance = $this->_getMapDistance($request->get('origin'), $request->get('destination'));
        } catch (\Exception $ex) {
            //throw $th;
            $distance = Haversine::getDistance($origin[0], $origin[1], $destination[0], $destination[1]);
        }
        $order = new Order;
        $order->order_id = bin2hex(openssl_random_pseudo_bytes(10));
        $order->origin_lat = $origin[0];
        $order->origin_lng = $origin[1];
        $order->destination_lat = $destination[0];
        $order->destination_lng = $destination[1];
        $order->distance = $distance;
        $order->status = 'UNASSIGNED';
        $order->created_at = date('Y-m-d H:i:s');
        $order->updated_at = date('Y-m-d H:i:s');
        $order->save();

        $response = ['id'=>$order->id, 'distance'=>$order->distance, 'status'=>"UNASSIGNED"];
        return response()->json($response, 201);
    }


/**
 * @OA\PATCH(path="/orders/{orderID}",
 *   tags={"Take a Order"},
 *   summary="Take a order",
 *   description="Take order by Order Id where Order status is UNASSIGNED",
 *   operationId="takeOrder",
*     @OA\Parameter(
*         name="orderID",
*         in="path",
*         description="Valid Order Id",
*         required=true,
*         @OA\Schema(
*         type="integer",
*          format="int64"
*       ),
*         style="form"
*     ),
 *     @OA\RequestBody(
 *         @OA\MediaType(
 *             mediaType="application/json",
 *             @OA\Schema(
 *                 @OA\Property(
 *                     property="status",
 *                     type="string",
 *                 ),
 *                 example={"status": "TAKEN"}
 *             )
 *         )
 *     ),
 * 
 *   @OA\Response(
 *     response=200,
 *     description="Order successfully Taken",
 *     @OA\Schema(ref="#/components/schemas/Order")
 *   ),
 *   @OA\Response(response=400, description="Bad Request"),
 *   @OA\Response(response=409, description="Conflict Order/Already Taken"),
 *   @OA\Response(response=422, description="Invalid Parameters")
 * 
 * )
 */


/**
 * Take a Order
 * Order is taken if it's status is UNASSIGNED and provided a Valid Order ID. 
 * 
 * @param Request $request
 * @param $orderID | int, Order Id 
 * @return Response, json
 */ 
    public function take(Request $request, $orderID)
    {
        $validationResponse = OrderValidation::takeRequestValidate($request);
        if(!$validationResponse['status']) {
            return
            response()->json(['error'=>$validationResponse['errors']], 422);
        }
        $order = Order::findOrFail($orderID);
        $orderUpdate = Order::where(['id'=>$orderID, 'status'=>'UNASSIGNED'])->update(['status'=>'TAKEN', 'updated_at'=>date('Y-m-d H:i:s')]);
        // if(!$order) return response()->json(['error'=>'Invalid Order Id'], 200);
        $response = $orderUpdate == 1 ? ['status'=>'TAKEN'] : ['error'=>'ORDER_ALREADY_TAKEN'];
        $statusCode = $orderUpdate == 1 ? 200 : 409;        // 409 ~conflict
        return response()->json($response, $statusCode);
    }


    
    /**
     * @OA\Get(path="/orders",
     *   tags={"Order List"},
     *   summary="Return Order List",
     *   description="Returns Order id, status, disatnce List",
     *   operationId="getOrderList",
     *   parameters={},
     *   @OA\Response(
     *     response=200,
     *     description="successful operation",
     *     @OA\Schema(
     *       additionalProperties={
     *         "type":"integer",
     *         "format":"int32"
     *       }
     *     )
     *   ),
     * )
     */


/**
 * Orders List
 *Get Order is taken if it's status is UNASSIGNED and provided a Valid Order ID. 
 * 
 * @param Request $request
 * @param page | int, Page Number 
 * @param limit | int, Order Limit 
 * @return Response, json
 */ 
    public function list(Request $request)
    {
        $validationResponse = OrderValidation::listRequestValidate($request);
        if(!$validationResponse['status']) {
            return
            response()->json(['error'=>$validationResponse['errors']], 422);
        }

        $limit = $request->get('limit') ? : $this->defaultLimit;
        $page = $request->get('page') ? : $this->defaultPage;
        
        $orders = Order::select('id', 'distance', 'status')->paginate($limit);
        $orders->appends(['limit' => $limit, 'page'=>$page]);
        return response()->json($orders, 200);
        // to fetch only data
        return response()->json($orders->items(), 200);
    }



    private function _getMapDistance(array $origin, array $destination)
    {
        $url = "https://maps.googleapis.com/maps/api/distancematrix/OUTPUT_FORMAT?units=UNIT&origins=ORIGIN_VALUES&destination=DESTINATION_VALUES&key=API_KEY";
        $origin = implode(",", $origin);
        $destination = implode(",", $destination);
        // $coordinates = $origin . "|" . $destination;
        $searchKeys = ['OUTPUT_FORMAT', 'UNIT', 'ORIGIN_VALUES', 'DESTINATION_VALUES', 'API_KEY'];
        if(env('GOOGLE_MAP_KEY') == "")
        {
            abort(422, 'Google Map API_KEY is not set.');
            response()->json(['error'=>$validationResponse['errors']], 422);
        }
        $replacements = ['json', 'metric', $origin, $destination, env('GOOGLE_MAP_KEY')];
        $url = str_replace($searchKeys, $replacements, $url);

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
            $distance = $content['rows'][0]['elements'][0]['distance']['text'];    // in Km
        } catch (RequestException $ex) {
            abort(404, 'Something went wrong with Google Map API.');
        }

    }




}