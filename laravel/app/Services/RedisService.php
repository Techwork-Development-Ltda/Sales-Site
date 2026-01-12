<?php

namespace App\Services;

use Illuminate\Support\Facades\Redis;
use Throwable;

class RedisService
{
    protected string $connection;

    public function __construct(string $connection = 'default')
    {
        $this->connection = $connection;
    }

    protected function client()
    {
        return Redis::connection($this->connection);
    }

    public function ping(): string
    {
        return $this->client()->ping();
    }

    public function set(string $key, string $value, ?int $ttlSeconds = null): bool
    {
        if ($ttlSeconds === null) {
            return (bool) $this->client()->set($key, $value);
        }

        return (bool) $this->client()->setex($key, $ttlSeconds, $value);
    }

    public function get(string $key): ?string
    {
        return $this->client()->get($key);
    }

    public function del(string ...$keys): int
    {
        return $this->client()->del($keys);
    }

    public function hgetall(string $key): array
    {
        $data = $this->client()->hgetall($key);

        if (empty($data)) {
            return [];
        }

        return $data;
    }

    /**
     * Método genérico (opcional)
     * Permite executar qualquer comando Redis
     */
    public function command(string $command, array $arguments = [])
    {
        return $this->client()->command($command, $arguments);
    }
}
