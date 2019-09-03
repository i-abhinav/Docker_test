<?php

namespace App\Repositories;

# app/repositories/OrderRepositoryInterface.php
 
interface OrderRepositoryInterface {
 
    public function list($p, $l);
  
    public function create($input);

    public function take($id);
 
}