<?php

namespace Tests\Unit\Http\Controllers;

use Tests\TestCase;
use App\Http\Controllers\API\RoleController;
use App\Services\RoleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Pagination\LengthAwarePaginator;
use Mockery;
use Spatie\Permission\Models\Role;

class RoleControllerTest extends TestCase
{
    protected RoleService $roleService;
    protected RoleController $controller;

    protected function setUp(): void
    {
        parent::setUp();

        $this->roleService = Mockery::mock(RoleService::class);
        $this->controller = new RoleController($this->roleService);
    }

    public function test_index_returns_collection(): void
    {
        $role = new Role(['name' => 'admin', 'guard_name' => 'web']);
        $paginator = new LengthAwarePaginator([$role], 1, 15);

        $this->roleService->shouldReceive('paginate')->once()->andReturn($paginator);

        $request = new Request(['page' => 1]);
        $response = $this->controller->index($request);

        $this->assertInstanceOf(AnonymousResourceCollection::class, $response);
    }

    public function test_store_returns_json_response():void
    {
        $role = new Role(['name' => 'editor', 'guard_name' => 'web']);
        $this->roleService->shouldReceive('createRole')->once()->andReturn($role);

        $request = Mockery::mock('App\Http\Requests\Roles\StoreRoleRequest');
        $request->shouldReceive('validated')->once()->andReturn(['name' => 'editor', 'guard_name' => 'web']);

        $response = $this->controller->store($request);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_show_returns_json_response(): void
    {
        $role = new Role(['name' => 'admin', 'guard_name' => 'web']);
        $this->roleService->shouldReceive('getById')->once()->andReturn($role);

        $response = $this->controller->show(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_update_returns_json_response(): void
    {
        $role = new Role(['name' => 'admin', 'guard_name' => 'web']);
        $this->roleService->shouldReceive('updateRole')->once()->andReturn($role);

        $request = Mockery::mock('App\Http\Requests\Roles\UpdateRoleRequest');
        $request->shouldReceive('validated')->once()->andReturn(['name' => 'admin']);

        $response = $this->controller->update(1, $request);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    public function test_delete_returns_json_response(): void
    {
        $this->roleService->shouldReceive('deleteRole')->once()->with(1);

        $response = $this->controller->delete(1);
        $this->assertInstanceOf(JsonResponse::class, $response);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
