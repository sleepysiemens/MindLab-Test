<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\API\UserController;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use App\Models\User;

class UserControllerTest extends TestCase
{
    protected UserService $userService;
    protected UserController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userService = Mockery::mock(UserService::class);
        $this->controller = new UserController($this->userService);
    }

    public function test_index_returns_collection(): void
    {
        $user = new User(['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'is_active' => 1]);
        $paginator = new LengthAwarePaginator([$user], 1, 15);

        $this->userService->shouldReceive('paginate')->once()->andReturn($paginator);

        $request = new Request(['page' => 1]);
        $response = $this->controller->index($request);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
    }

    public function test_store_returns_json_response(): void
    {
        $user = new User(['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'is_active' => 1]);
        $this->userService->shouldReceive('createUser')->once()->andReturn($user);

        $request = Mockery::mock('App\Http\Requests\Users\StoreUserRequest');
        $request->shouldReceive('validated')->once()->andReturn([
            'name' => 'John',
            'email' => 'john@example.com',
            'password' => 'secret',
            'is_active' => 1
        ]);

        $response = $this->controller->store($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_show_returns_json_response(): void
    {
        $user = new User(['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'is_active' => 1]);
        $this->userService->shouldReceive('getById')->once()->andReturn($user);

        $response = $this->controller->show(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_update_returns_json_response(): void
    {
        $user = new User(['id' => 1, 'name' => 'John', 'email' => 'john@example.com', 'is_active' => 1]);
        $this->userService->shouldReceive('updateUser')->once()->andReturn($user);

        $request = Mockery::mock('App\Http\Requests\Users\UpdateUserRequest');
        $request->shouldReceive('validated')->once()->andReturn(['name' => 'John Doe']);

        $response = $this->controller->update(1, $request);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_delete_returns_json_response(): void
    {
        $this->userService->shouldReceive('deleteUser')->once()->with(1);

        $response = $this->controller->delete(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_deactivate_returns_json_response(): void
    {
        $this->userService->shouldReceive('deactivateUser')->once()->with(1);

        $response = $this->controller->deactivate(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
