<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\API\AbstractAPIController;
use Illuminate\Http\Resources\Json\JsonResource;

class AbstractAPIControllerTest extends TestCase
{
    protected AbstractAPIController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new AbstractAPIController();
    }

    public function test_error_handle_returns_json(): void
    {
        $response = $this->controller->errorHandle('Something went wrong', 'DETAIL', 400);

        $responseData = $response->getData(true);

        $this->assertTrue($responseData['failed']);
        $this->assertEquals('Something went wrong', $responseData['message']);
        $this->assertEquals('DETAIL', $responseData['error']);
        $this->assertEquals(400, $response->getStatusCode());
    }

    public function test_get_response_with_message_and_data(): void
    {
        $resource = JsonResource::make(['key' => 'value']);
        $response = $this->controller->getResponse(false, 'Success', $resource->resolve(), 201);

        $responseData = $response->getData(true);

        $this->assertFalse($responseData['failed']);
        $this->assertEquals('Success', $responseData['message']);
        $this->assertEquals(['key'=>'value'], $responseData['data']);
        $this->assertEquals(201, $response->getStatusCode());
    }

    public function test_get_response(): void
    {
        $response = $this->controller->getResponse();

        $responseData = $response->getData(true);

        $this->assertFalse($responseData['failed']);
        $this->assertArrayNotHasKey('message', $responseData);
        $this->assertArrayNotHasKey('data', $responseData);
        $this->assertEquals(200, $response->getStatusCode());
    }
}
