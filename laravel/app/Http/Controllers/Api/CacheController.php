<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

use App\Http\Controllers\Controller;
use App\Services\RedisService;

class CacheController extends Controller
{
    protected RedisService $redis;
    
    public function __construct(RedisService $redis)
    {
        $this->redis = $redis;
    }
    
    public function store(Request $request) : JsonResponse
    {
        $credentials = $request->only(['key', 'value', 'ttl']);
        $key = isset($credentials['key']) ? trim($credentials['key']) : null;
        $value = $credentials['value'] ?? null;
        $ttl = $credentials['ttl'] ?? 3600;
        $ttl = is_numeric($ttl) ? (int)$ttl : 3600;

        if (! $key || !$value) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid parameters.',
                'errors' => ['key' => 'The key field is required.', 'value' => 'The value field is required.'],
                'data' => []
            ], 400);
        }

        $this->redis->set($key, $value, $ttl);
        return response()->json([
            'status' => true,
            'message' => 'Cache stored successfully.',
            'errors' => [],
            'data' => ['key' => $key]
        ]);
    }

    public function show(Request $request, string $key) : JsonResponse
    {
        $value = $this->redis->get($key);
        if ($value === null) {
            return response()->json([
                'status' => false,
                'message' => 'Key not found in cache.',
                'errors' => [],
                'data' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Cache retrieved successfully.',
            'errors' => [],
            'data' => ['key' => $key, 'value' => $value]
        ]);
    }

    public function destroy(Request $request, string $key) : JsonResponse
    {
        $deleted = $this->redis->del($key);
        return response()->json([
            'status' => true,
            'message' => 'Cache removed successfully.',
            'errors' => [],
            'data' => ['deleted_count' => $deleted]
        ]);
    }
}