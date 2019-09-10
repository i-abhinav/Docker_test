<?php

namespace App\Repositories;

# app/repositories/OrderRepositoryInterface.php

interface OrderRepositoryInterface
{

    function list($page, $limit);

    public function create($input);

    public function take($id);

    public function show($id);

}
