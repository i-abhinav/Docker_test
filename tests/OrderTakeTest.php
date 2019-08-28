<?php


class OrderTakeTest extends TestCase
{
    /**
     * Take Order Test
     *
     * @return void
     */
    public function testTakeOrder()
    {
        $response = $this->call('PATCH', '/orders/{orderId}', ['orderId' => rand()]);
        $this->seeJsonEquals([
            'status'=>'TAKEN'
         ]);
    }
}
