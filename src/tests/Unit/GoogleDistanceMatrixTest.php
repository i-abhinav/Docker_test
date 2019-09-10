<?php

use App\Helpers\FakeData;
use App\Helpers\GoogleDistanceMatrix;

class GoogleDistanceMatrixTest extends TestCase
{

    public function setUp(): void
    {
        parent::setUp();
        $this->app->instance('middleware.disable', true);

        $this->googleMock = $this->createMock(GoogleDistanceMatrix::class);
    }

    public function tearDown(): void
    {
        \Mockery::close();
    }

/** @test */
    public function testPassCoordinates()
    {
        $validData = FakeData::validCoordinates();

        $g = new GoogleDistanceMatrix();
        $distance = $g->getMapDistance($validData['origin'], $validData['destination']);

        $this->googleMock
            ->method('getMapDistance')
            ->with($validData['origin'], $validData['destination'])
            ->willReturn($distance);

        $this->assertNotEmpty($distance);
        $this->assertInternalType('integer', $distance, "Got a " . gettype($distance) . " instead of a integer");
        $this->assertNotEquals(0, $distance);

    }

    /** @test */
    public function testSameCoordinates()
    {
        $data = FakeData::sameCoordiates();

        $g = new GoogleDistanceMatrix();

        $distance = $g->getMapDistance($data['origin'], $data['destination']);

        $this->googleMock
            ->method('getMapDistance')
            ->with($data['origin'], $data['destination'])
            ->willReturn(true);

        $this->assertInternalType('integer', $distance, "Got a " . gettype($distance) . " instead of a integer");
        $this->assertEquals(0, $distance);

    }

    public function testZeroResultException()
    {
        $zeroResultdata = ['origin' => ['-16.80532,30.14374'], 'destination' => ['15.68929,131.56186']];
        $g = new GoogleDistanceMatrix();
        $this->expectException(\App\Exceptions\GoogleMapAPIException::class);
        $g->getMapDistance($zeroResultdata['origin'], $zeroResultdata['destination']);
    }

}
