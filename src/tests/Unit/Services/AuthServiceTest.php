<?php

namespace Tests\Unit\Services;

use Symfony\Component\Finder\Exception\AccessDeniedException;
use Tests\TestCase;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;
use Mockery;

class AuthServiceTest extends TestCase
{
    protected AuthService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new AuthService();
    }

    /**
     * @throws Throwable
     */
    public function test_login_successful_when_user_active()
    {
        $user = User::factory()->make([
            'is_active' => true,
        ]);

        Auth::shouldReceive('attempt')
            ->once()
            ->with([
                'email' => 'test@example.com',
                'password' => 'secret'
            ])->andReturn(true);

        Auth::shouldReceive('user')
            ->once()
            ->andReturn($user);

        $result = $this->service->login([
            'email' => 'test@example.com',
            'password' => 'secret'
        ]);

        $this->assertTrue((bool) $result);
    }

    /**
     * @throws Throwable
     */
    public function test_login_throws_authentication_exception_when_invalid_credentials()
    {
        Auth::shouldReceive('attempt')->once()->andReturn(false);

        $this->expectException(AuthenticationException::class);
        $this->expectExceptionMessage('Неверный email или пароль.');

        $this->service->login([
            'email' => 'wrong@example.com',
            'password' => 'wrong'
        ]);
    }

    /**
     * @throws Throwable
     */
    public function test_login_throws_access_denied_exception_when_user_inactive()
    {
        $inactiveUser = User::factory()->make([
            'is_active' => false,
        ]);

        Auth::shouldReceive('attempt')->once()->andReturn(true);
        Auth::shouldReceive('user')->once()->andReturn($inactiveUser);
        Auth::shouldReceive('logout')->once();

        $this->expectException(AccessDeniedException::class);
        $this->expectExceptionMessage('Учетная запись деактивирована.');

        $this->service->login([
            'email' => 'test@example.com',
            'password' => 'secret'
        ]);
    }

    public function test_refresh_returns_new_token()
    {
        Auth::shouldReceive('refresh')->once()->andReturn('new-token');

        $result = $this->service->refresh();

        $this->assertEquals('new-token', $result);
    }

    /**
     * @throws Throwable
     */
    public function test_change_password_throws_validation_exception_on_wrong_old_password()
    {
        $user = User::factory()->make([
            'password' => bcrypt('old-password'),
        ]);

        Hash::shouldReceive('check')->once()->with('wrong-old', $user->password)->andReturn(false);

        Auth::shouldReceive('user')->andReturn($user);

        $this->expectException(\Nette\Schema\ValidationException::class);
        $this->expectExceptionMessage('Неверный пароль.');

        $this->service->changePassword([
            'old_password' => 'wrong-old',
            'new_password' => 'new-password'
        ]);
    }

    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
