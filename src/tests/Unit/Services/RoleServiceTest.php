<?php
namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\RoleService;
use Mockery;
use Illuminate\Support\Facades\Cache;
use Spatie\Permission\Models\Role;
use Throwable;

class RoleServiceTest extends TestCase
{
    protected RoleService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new RoleService();
    }

    /**
     * @throws Throwable
     */
    public function test_update_role_calls_update()
    {
        $role = Mockery::mock(Role::class);
        $role->shouldReceive('update')->once()->with(['name'=>'manager'])->andReturnTrue();

        $serviceMock = Mockery::mock(RoleService::class)->makePartial();
        $serviceMock->shouldReceive('getById')->once()->with(1)->andReturn($role);

        $result = $serviceMock->updateRole(1, ['name'=>'manager']);

        $this->assertSame($role, $result);
    }

    /**
     * @throws Throwable
     */
    public function test_delete_role_calls_delete()
    {
        $role = Mockery::mock(Role::class);
        $role->shouldReceive('delete')->once()->andReturnTrue();

        $serviceMock = Mockery::mock(RoleService::class)->makePartial();
        $serviceMock->shouldReceive('getById')->once()->with(1)->andReturn($role);

        $serviceMock->deleteRole(1);
        $this->assertTrue(true);
    }

    public function test_paginate_returns_length_aware_paginator()
    {
        $paginator = Mockery::mock('Illuminate\Pagination\LengthAwarePaginator');

        Cache::shouldReceive('tags')->once()->with(['roles'])->andReturnSelf();
        Cache::shouldReceive('rememberForever')->once()->andReturn($paginator);

        $result = $this->service->paginate(10);

        $this->assertSame($paginator, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
