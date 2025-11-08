<?php

// config/open-telemetry.php (modelo recomendado)
return [
    'default_trace_name' => null,

    // Envia spans no formato Zipkin v2
    'drivers' => [
        Spatie\OpenTelemetry\Drivers\HttpDriver::class => [
            'url' => 'http://collector:9411/api/v2/spans',
        ],
    ],

    // Amostra tudo (depois você pode trocar por LotterySampler)
    'sampler' => Spatie\OpenTelemetry\Support\Samplers\AlwaysSampler::class,

    'trace_tag_providers' => [
        \Spatie\OpenTelemetry\Support\TagProviders\DefaultTagsProvider::class,
    ],

    'span_tag_providers' => [],

    'queue' => [
        'make_queue_trace_aware' => true,
        'all_jobs_are_trace_aware_by_default' => true,
        'all_jobs_auto_start_a_span' => true,
        'trace_aware_jobs' => [],
        'not_trace_aware_jobs' => [],
    ],

    'actions' => [
        'make_queue_trace_aware' => Spatie\OpenTelemetry\Actions\MakeQueueTraceAwareAction::class,
    ],

    'stopwatch' => Spatie\OpenTelemetry\Support\Stopwatch::class,
    'id_generator' => Spatie\OpenTelemetry\Support\IdGenerator::class,
];