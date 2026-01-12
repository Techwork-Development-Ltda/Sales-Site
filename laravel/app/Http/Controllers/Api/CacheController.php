<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\RedisService;

class CacheController extends Controller
{
    protected RedisService $redis;
    
    public function __construct(RedisService $redis)
    {
        $this->redis = $redis;
    }
    
    public function store(Request $request)
    {
        $credentials = $request->only(['key', 'value', 'ttl']);
        $key = isset($credentials['key']) ? trim($credentials['key']) : null;
        $value = $credentials['value'] ?? null;
        $ttl = $credentials['ttl'] ?? 3600;

        if (! $key || !$value || !ctype_digit((string)$ttl)) {
            return response()->json([
                'status' => false,
                'message' => 'Key e value são obrigatórios',
                'erros' => ['key' => 'Campo obrigatório', 'value' => 'Campo obrigatório'],
                'dados' => []
            ], 400);
        }

        // Deixa as exceptions serem lançadas
        // O Laravel automaticamente chama o método render() delas
        $this->redis->set($key, $value, $ttl);
        
        return response()->json([
            'status' => true,
            'message' => 'Cache armazenado com sucesso.',
            'erros' => [],
            'dados' => ['key' => $key]
        ]);
    }

    public function show(Request $request, string $key)
    {
        $value = $this->redis->get($key);
        
        if ($value === null) {
            return response()->json([
                'status' => false,
                'message' => 'Key não encontrada no cache.',
                'erros' => [],
                'dados' => []
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Cache recuperado com sucesso.',
            'erros' => [],
            'dados' => ['key' => $key, 'value' => $value]
        ]);
    }

    public function destroy(Request $request, string $key)
    {
        $deleted = $this->redis->del($key);
        
        return response()->json([
            'status' => true,
            'message' => 'Cache removido com sucesso.',
            'erros' => [],
            'dados' => ['deleted_count' => $deleted]
        ]);
    }
}