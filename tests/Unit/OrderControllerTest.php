<?php

// use Illuminate\Http\Request;
use App\Models\Order;
use App\Helpers\FakeData;
use App\Helpers\Haversine;
use App\Helpers\GoogleDistanceMatrix;
use App\Http\Validations\OrderValidator;

class OrderControllerTest extends TestCase
{

    public function setUp(): void
    {        
        parent::setUp();
        $this->faker = Faker\Factory::create();
        $this->app->instance('middleware.disable', true);
        
        // $this->orderMock = \Mockery::mock('Eloquent', 'Order');
        $this->orderMock = Mockery::mock('Order');

        // $this->orderMock = \Mockery::mock('App\Repositories\OrderRepositoryInterface');

        $this->validatorMock = $this->createMock(OrderValidator::class);
    }


    public function tearDown(): void
    {
        \Mockery::close();
    }

/** @test */
    public function testStoreSuccess()
    {
        $validData = FakeData::validCoordinates();

        $this->validatorMock
        ->method('storeRequestValidate')
        ->with($validData)
        ->willReturn(true);
        
        $this->orderMock->shouldReceive('create')->with($validData)->andReturn(true);

        $response = $this->call('POST', '/orders', $validData);
        $data = (array) $response->getData();

        $orderRepository = $this->app->instance(App\Repositories\OrderEloquentRepository::class, array($this->orderMock));

        $this->assertEquals(200, $this->response->status());

        $this->assertInternalType('array', $data);

        $this->assertInstanceOf('Illuminate\Http\JsonResponse', $response);

    }
        

    public function testStoreFails()
    {
        // Set stage for a failed validation
        $invalidData = FakeData::invalidLatitudeLongitude();
    
        $this->app->instance('Order', $this->orderMock);
    
        $this->validatorMock
        ->method('storeRequestValidate')
        ->with($invalidData)
        ->willReturn(Mockery::mock(['fails' => 'true']));
        
        // $this->call('POST', 'posts');
        $response = $this->call('POST', '/orders', $invalidData);
            
        // Failed validation should reeturn 422 response code
        $this->assertEquals(422, $this->response->status());
        
        $data = (array) $response->getData();
        // The errors should have error key in response
        $this->assertArrayHasKey('error', $data);
        
    }

    /** @test */
    public function testTakeSuccess()
    {
        $validData = ['status'=>'TAKEN'];
        $orderID = 2;

        $this->validatorMock
        ->method('takeRequestValidate')
        ->with($validData)
        ->willReturn(true);
        
        $this->orderMock->shouldReceive('take')->with($orderID)->andReturn(true);

        $response = $this->call('PATCH', '/orders/6', $validData);
        $data = (array) $response->getData();

        $orderRepository = $this->app->instance(App\Repositories\OrderEloquentRepository::class, array($this->orderMock));

        $this->assertEquals(200, $this->response->status());

        $this->assertInternalType('array', $data);

        $this->assertInstanceOf('Illuminate\Http\JsonResponse', $response);

    }


    public function testTakeFails()
    {
        // Set stage for a failed validation
        $invalidData = ['status'=>'TAK123EN'];
        $orderID = 2;
    
        $this->app->instance('Order', $this->orderMock);
    
        $this->validatorMock
        ->method('takeRequestValidate')
        ->with($invalidData)
        ->willReturn(Mockery::mock(['fails' => 'true']));
        
        // $this->call('POST', 'posts');
        $response = $this->call('PATCH', '/orders/6', $invalidData);
            
        // Failed validation should reeturn 422 response code
        $this->assertEquals(422, $this->response->status());
        
        $data = (array) $response->getData();
        // The errors should have error key in response
        $this->assertArrayHasKey('error', $data);
        
    }

    /** @test */
    public function testListSuccess()
    {
        $query = 'page=1&limit=5';
        $page = 1;
        $limit = 5;

        $this->validatorMock
        ->method('listRequestValidate')
        ->with($query)
        ->willReturn(true);
        
        $this->orderMock->shouldReceive('list')->with($page, $limit)->andReturn(true);

        $response = $this->call('GET', '/orders?'. $query);
        $data = (array) $response->getData();

        $orderRepository = $this->app->instance(App\Repositories\OrderEloquentRepository::class, array($this->orderMock));

        $this->assertEquals(200, $this->response->status());

        $this->assertInternalType('array', $data);

        $this->assertArrayHasKey('id', (array) $data[0]);
        $this->assertArrayHasKey('distance', (array) $data[0]);
        $this->assertArrayHasKey('status', (array) $data[0]);

        $this->assertInstanceOf('Illuminate\Http\JsonResponse', $response);

    }


    public function testListFails()
    {
        // Set stage for a failed validation
        $query = 'page=-1&limit=-5';
        $page = -1;
        $limit = -5;
    
        $this->app->instance('Order', $this->orderMock);
    
        $this->validatorMock
        ->method('listRequestValidate')
        ->with($query)
        ->willReturn(Mockery::mock(['fails' => 'true']));
        
        // $this->call('POST', 'posts');
        $response = $this->call('GET', '/orders?'. $query);
            
        // Failed validation should reeturn 422 response code
        $this->assertEquals(422, $this->response->status());
        
        $data = (array) $response->getData();
        // The errors should have error key in response
        $this->assertArrayHasKey('error', $data);
        
    }


}