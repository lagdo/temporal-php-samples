<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sample\Temporal\Factory\RuntimeFactory;
use Temporal\Client\WorkflowClientInterface;
use Temporal\OpenTelemetry\Tracer;

use function config;

class RuntimeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(Tracer::class, function() {
            $serviceName = config('temporal.runtime.otel.service');
            $collectorEndpoint = config('temporal.runtime.otel.collector');
            return RuntimeFactory::tracer($serviceName, $collectorEndpoint);
        });

        $this->app->bind(WorkflowClientInterface::class, function() {
            $serverAddress = config('temporal.runtime.server.endpoint');
            $tracer = $this->app->make(Tracer::class);
            return RuntimeFactory::client($serverAddress, $tracer);
        });
    }
}
