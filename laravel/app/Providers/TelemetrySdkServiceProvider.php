<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OpenTelemetry\SDK\Sdk;
use OpenTelemetry\SDK\Trace\TracerProviderBuilder;
use OpenTelemetry\SDK\Trace\SpanProcessor\SimpleSpanProcessor;
use OpenTelemetry\Contrib\Otlp\SpanExporter as OtlpSpanExporter;
use OpenTelemetry\Contrib\Otlp\OtlpHttpTransportFactory;

class TelemetrySdkServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        if (!filter_var(env('OTEL_ENABLED', true), FILTER_VALIDATE_BOOL)) {
            return;
        }

        $endpoint = rtrim(env('OTEL_EXPORTER_OTLP_ENDPOINT', 'http://collector:4318'), '/').'/v1/traces';

        $transport = (new OtlpHttpTransportFactory())->create($endpoint, 'application/x-protobuf');
        $exporter  = new OtlpSpanExporter($transport);

        $tracerProvider = (new TracerProviderBuilder())
            ->addSpanProcessor(new SimpleSpanProcessor($exporter)) // depois podemos trocar por Batch
            ->build();

        Sdk::builder()
            ->setTracerProvider($tracerProvider)
            ->buildAndRegisterGlobal();

        register_shutdown_function(static fn () => $tracerProvider->shutdown());
    }
}