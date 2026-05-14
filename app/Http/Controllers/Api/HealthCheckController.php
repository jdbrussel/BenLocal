<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis;

class HealthCheckController extends Controller
{
    public function __invoke()
    {
        $status = [
            'status' => 'up',
            'timestamp' => now()->toIso8601String(),
            'services' => [
                'database' => $this->checkDatabase(),
                'cache' => $this->checkCache(),
                'redis' => $this->checkRedis(),
            ]
        ];

        $hasError = collect($status['services'])->contains('down');

        return response()->json($status, $hasError ? 503 : 200);
    }

    protected function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            return 'up';
        } catch (\Exception $e) {
            return 'down';
        }
    }

    protected function checkCache()
    {
        try {
            Cache::put('health_check', true, 1);
            return Cache::get('health_check') ? 'up' : 'down';
        } catch (\Exception $e) {
            return 'down';
        }
    }

    protected function checkRedis()
    {
        try {
            // Use connection() to ensure we're not just calling it on the facade without a driver
            return Redis::connection()->ping() ? 'up' : 'down';
        } catch (\Exception $e) {
            return 'down';
        }
    }
}
