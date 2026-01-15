<?php

namespace App\Http\Middleware;

use Closure;
use Throwable;
use OpenTelemetry\API\Globals;

class TraceRequests
{
    public function handle($request, Closure $next)
    {
        $tp = Globals::tracerProvider();
        $tracer = $tp->getTracer('http');

        $name = sprintf('%s %s', $request->getMethod(), $request->path() ?: '/');

        $span = $tracer->spanBuilder($name)->startSpan();
        $span->setAttribute('http.method', $request->getMethod());
        $span->setAttribute('http.target', $request->getRequestUri());
        $span->setAttribute('service.name', config('app.name'));

        try {
            $response = $next($request);
            if (method_exists($response, 'getStatusCode')) {
                $span->setAttribute('http.status_code', $response->getStatusCode());
            }
            return $response;
        } catch (Throwable $e) {
            $span->recordException($e);
            throw $e;
        } finally {
            $span->end();
        }
    }
}
