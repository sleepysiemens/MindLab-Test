<?php

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class RoleObserver
{
    public function saved(): void
    {
        $this->invalidate();
    }

    public function deleted(): void
    {
        $this->invalidate();
    }

    private function invalidate(): void
    {
        Cache::tags(['roles'])->flush();
        Cache::tags(['users'])->flush();
    }
}
