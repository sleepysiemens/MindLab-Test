<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Requests\Auth\LoginRequest;
use Tests\TestCase;
use App\Http\Controllers\API\AuthController;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Mockery;

class AuthControllerTest extends TestCase
{
    protected AuthService $authService;
    protected AuthController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = Mockery::mock(AuthService::class);
        $this->controller = new AuthController($this->authService);
    }

    public function test_login_successful(): void
    {
        $this->authService->shouldReceive('login')->once()->andReturn('token123');

        $request = Mockery::mock('alias:App\Http\Requests\Auth\LoginRequest');
        $request->shouldReceive('validated')->once()->andReturn(['email'=>'test@test.com','password'=>'secret']);

        /** @var LoginRequest $request */
        $response = $this->controller->login($request);

        $data = $response->getData(true);
        $this->assertEquals('Успешная авторизация.', $data['message']);
        $this->assertEquals('token123', $data['data']['token']);
    }

    public function test_login_authentication_exception(): void
    {
        $this->authService->shouldReceive('login')->once()->andThrow(new AuthenticationException('Invalid credentials'));
        $request = Mockery::mock('alias:App\Http\Requests\Auth\LoginRequest');
        $request->shouldReceive('validated')->once()->andReturn(['email'=>'test@test.com','password'=>'secret']);

        /** @var LoginRequest $request */
        $response = $this->controller->login($request);
        $this->assertEquals(401, $response->getStatusCode());
    }

    public function test_login_access_denied_exception(): void
    {
        $this->authService->shouldReceive('login')->once()->andThrow(new AccessDeniedException('Account inactive'));
        $request = Mockery::mock('alias:App\Http\Requests\Auth\LoginRequest');
        $request->shouldReceive('validated')->once()->andReturn(['email'=>'test@test.com','password'=>'secret']);

        /** @var LoginRequest $request */
        $response = $this->controller->login($request);
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function test_logout_returns_json_response(): void
    {
        $this->authService->shouldReceive('logout')->once();

        $response = $this->controller->logout();
        $data = $response->getData(true);
        $this->assertEquals('Вы успешно вышли из системы.', $data['message']);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
