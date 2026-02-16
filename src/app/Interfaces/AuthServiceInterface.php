<?php

namespace App\Interfaces;

interface AuthServiceInterface
{
    public function login(array $data): string;
    public function logout(): void;
    public function changePassword(array $data): void;
}
