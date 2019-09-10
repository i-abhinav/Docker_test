<?php

namespace App\Exceptions;

use Exception;

class GoogleMapAPIException extends Exception
{
    public function __construct($message)
    {
        parent::__construct($message);
    }

}
