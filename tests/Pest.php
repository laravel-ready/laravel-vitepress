<?php

use Illuminate\Contracts\Auth\Authenticatable;
use LaravelReady\VitePress\Tests\TestCase;

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
*/

uses(TestCase::class)->in('Feature', 'Unit');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
*/

function createMockUser(array $capabilities = []): Authenticatable
{
    return new class($capabilities) implements Authenticatable
    {
        private array $capabilities;

        public function __construct(array $capabilities)
        {
            $this->capabilities = $capabilities;
        }

        public function getAuthIdentifierName(): string
        {
            return 'id';
        }

        public function getAuthIdentifier(): mixed
        {
            return 1;
        }

        public function getAuthPassword(): string
        {
            return 'password';
        }

        public function getAuthPasswordName(): string
        {
            return 'password';
        }

        public function getRememberToken(): ?string
        {
            return null;
        }

        public function setRememberToken($value): void
        {
        }

        public function getRememberTokenName(): string
        {
            return 'remember_token';
        }

        public function hasAnyRole(array $roles): bool
        {
            return ! empty(array_intersect($this->capabilities['roles'] ?? [], $roles));
        }

        public function hasAnyPermission(array $permissions): bool
        {
            return ! empty(array_intersect($this->capabilities['permissions'] ?? [], $permissions));
        }
    };
}
