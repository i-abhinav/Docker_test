<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    const STATUS_UNASSIGNED = 'UNASSIGNED';
    const STATUS_ASSIGNED = 'TAKEN';

    protected $table = 'orders';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'status', 'origin_lat', 'origin_lng', 'destination_lat', 'destination_lng', 'distance' 
    ];

    
}
