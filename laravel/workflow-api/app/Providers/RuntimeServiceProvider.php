<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sample\Temporal\Factory\RuntimeFactory;
use Temporal\Client\WorkflowClientInterface;
use Temporal\OpenTelemetry\Tracer;

use function env;

class RuntimeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(Tracer::class, function() {
            $serviceName = env('OTEL_SERVICE_NAME', 'app-workflow-api');
            $collectorEndpoint = env('OTEL_COLLECTOR_ENDPOINT', 'http://collector.addr:4317');
            return RuntimeFactory::tracer($serviceName, $collectorEndpoint);
        });

        $this->app->bind(WorkflowClientInterface::class, function() {
            $serverAddress = env('TEMPORAL_SERVER_ENDPOINT', 'http://temporal.addr:7233');
            $tracer = $this->app->make(Tracer::class);
            return RuntimeFactory::client($serverAddress, $tracer);
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
