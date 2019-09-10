<?php

namespace App\Http\Controllers;

use App\Helpers\GoogleDistanceMatrix;
use App\Helpers\Messages;
use App\Http\Validations\OrderValidator;
use App\Models\Order;
use App\Repositories\OrderRepositoryInterface as OrderRepo;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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
 * @var Order $order, Order Model instance
 */
    protected $order;

    // inject order eloquent interface repository to controller
    public function __construct(OrderRepo $order)
    {
        $this->order = $order;
    }

/**
 * @var int, Default Order-List Limit
 */
    protected $defaultLimit = 5;

/**
 * @var int, Default Order-List Page
 */
    protected $defaultPage = 1;

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
 *                 example={"origin": {"28.644800", "77.308601"}, "destination": {"19.076090", "72.877426"}}
 *             )
 *         )
 *     ),
 *
 *   @OA\Response(
 *     response=200,
 *     description="successful operation",
 *     @OA\Schema(ref="#/components/schemas/Order")
 *   ),
 *   @OA\Response(response=400, description="Bad Request"),
 *   @OA\Response(response=422, description="Invalid Parameters")
 *
 * )
 */

    public function store(Request $request): JsonResponse
    {
        if (empty($request->all())) {
            return response()->json(['error' => Messages::get('errors.bad')], JsonResponse::HTTP_BAD_REQUEST);
        }

        $resp = OrderValidator::storeRequestValidate($request);
        if (!$resp['status']) {
            return response()->json(['error' => $resp['errors']], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $origin = $request->get('origin');
        $destination = $request->get('destination');

        $distance = GoogleDistanceMatrix::getMapDistance($origin, $destination);

        $inputs = [
            'origin_lat' => $origin[0],
            'origin_lng' => $origin[1],
            'destination_lat' => $destination[0],
            'destination_lng' => $destination[1],
            'distance' => $distance,
            'status' => Order::STATUS_UNASSIGNED,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ];
        $order = $this->order->create($inputs);

        $response = ['id' => $order->id, 'distance' => $order->distance, 'status' => $order->status];
        return response()->json($response, JsonResponse::HTTP_OK);
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
 *   @OA\Response(response=400, description="BAD_REQUEST"),
 *   @OA\Response(response=409, description="CONFLICT_ORDER_ALREADY_TAKEN"),
 *   @OA\Response(response=422, description="INVALID_PARAMETERS")
 *   @OA\Response(response=405, description="METHOD_NOT_ALLOWED")
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
    public function take(Request $request, $orderID): JsonResponse
    {
        $request['order_id'] = $orderID;
        $validationResponse = OrderValidator::takeRequestValidate($request);
        if (!$validationResponse['status']) {
            return response()->json(['error' => $validationResponse['errors']], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }
        try {
            $this->order->show($orderID);
        } catch (\Exception $ex) {
            throw new \Illuminate\Database\Eloquent\ModelNotFoundException(Messages::get('orders.id_not_exist'));
        }

        $orderUpdate = $this->order->take($orderID);
        $response = $orderUpdate == 1 ? ['status' => Messages::get('orders.success')] : ['error' => Messages::get('orders.order_conflict')];
        $statusCode = $orderUpdate == 1 ? JsonResponse::HTTP_OK : JsonResponse::HTTP_CONFLICT;
        // 409 ~conflict
        return response()->json($response, $statusCode);
    }

    /**
     * @OA\Get(path="/orders?page=:page&limit=:limit",
     *   tags={"Order List"},
     *   summary="Return Order List",
     *   description="Returns Order List data have order id, status, disatnce",
     *   operationId="getOrderList",
     *   parameters={},
     *   @OA\Parameter(
     *       name="page",
     *       in="path",
     *       description="Valid Page Number",
     *       required=true,
     *       @OA\Schema(
     *           type="integer",
     *           format="int64"
     *       ),
     *   @OA\Parameter(
     *       name="limit",
     *       in="path",
     *       description="Valid Order Limit",
     *       required=true,
     *       @OA\Schema(
     *           type="integer",
     *           format="int64"
     *       ),
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
    function list(Request $request): JsonResponse {
        $validationResponse = OrderValidator::listRequestValidate($request);
        if (!$validationResponse['status']) {
            return response()->json(['error' => $validationResponse['errors']], JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $limit = $request->get('limit') ?: $this->defaultLimit;
        $page = $request->get('page') ?: $this->defaultPage;

        $orders = $this->order->list($page, $limit);
        // to fetch only data
        return response()->json($orders->items(), JsonResponse::HTTP_OK);
    }

}
