<?php


class OrderPostTest extends TestCase
{
    /**
     * Order Create Test.
     *
     * @return void
     */
    public function testOrderCreation()
    {
        $this->json('POST', '/orders', ['name' => 'Sally']);
        $this->assertEquals(200, $response->status());
    }
}
