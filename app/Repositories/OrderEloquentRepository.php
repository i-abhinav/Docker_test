<?php namespace App\Repositories;
 
# app/repositories/EloquentOrderRepository.php
 
use App\Repositories\OrderRepositoryInterface;
use App\Models\Order;
 
class OrderEloquentRepository implements OrderRepositoryInterface {
 
    public function list($page, $limit)
    {
        $ordersList = Order::select('id', 'distance', 'status')->paginate($limit);
        return 
        $ordersList->appends(['limit' => $limit, 'page'=>$page]);
    }
 
    public function take($id)
    {
        $order = Order::findOrFail($id);
        return Order::where(['id'=>$id, 'status'=>'UNASSIGNED'])->update(['status'=>Order::STATUS_ASSIGNED, 'updated_at'=>date('Y-m-d H:i:s')]);
    }
 
    public function create($input)
    {
        return Order::create($input);
    }
 
}