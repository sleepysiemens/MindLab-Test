<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Services\UserService;
use Mockery;
use Illuminate\Support\Facades\Cache;

class UserServiceTest extends TestCase
{
    protected UserService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new UserService();
    }

    public function test_delete_user_calls_delete()
    {
        $user = Mockery::mock('App\Models\User');
        $user->shouldReceive('delete')->once()->andReturnTrue();

        $serviceMock = Mockery::mock(UserService::class)->makePartial();
        $serviceMock->shouldReceive('getById')->once()->with(1)->andReturn($user);

        $serviceMock->deleteUser(1);
        $this->assertTrue(true);
    }

    public function test_paginate_returns_length_aware_paginator()
    {
        $paginator = Mockery::mock('Illuminate\Pagination\LengthAwarePaginator');

        Cache::shouldReceive('tags')->once()->with(['users'])->andReturnSelf();
        Cache::shouldReceive('rememberForever')->once()->andReturn($paginator);

        $result = $this->service->paginate(10, 1);

        $this->assertSame($paginator, $result);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
