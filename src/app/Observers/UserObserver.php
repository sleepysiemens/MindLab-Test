<?php
declare(strict_types=1);

namespace App\Observers;

use Illuminate\Support\Facades\Cache;

class UserObserver
{
    public function saved(): void
    {
        $this->invalidateCache();
    }
    public function deleted(): void
    {
        $this->invalidateCache();
    }

    public function invalidateCache(): void
    {
        Cache::tags(['users'])->flush();
    }
}
