<?php

namespace App\Providers;

use App\Models\User;
use App\Observers\RoleObserver;
use App\Observers\UserObserver;
use App\Services\AuthService;
use App\Services\RoleService;
use App\Services\UserService;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use Spatie\Permission\Events\RoleAttached;
use Spatie\Permission\Events\RoleDetached;
use Spatie\Permission\Models\Role;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(UserService::class);
        $this->app->bind(RoleService::class);
        $this->app->bind(AuthService::class);
    }

    public function boot(): void
    {
        Role::observe(RoleObserver::class);
        User::observe(UserObserver::class);

        Event::listen(RoleAttached::class, fn () => Cache::tags(['users'])->flush());
        Event::listen(RoleDetached::class, fn () => Cache::tags(['users'])->flush());
    }
}
