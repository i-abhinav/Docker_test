<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class OrderListTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testOrderList()
    {
        $this->get('/orders');

        $this->assertEquals(200, $response->status());
    }
}
