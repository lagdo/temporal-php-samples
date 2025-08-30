<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Sample\Temporal\Factory\RuntimeFactory;
use Sample\Temporal\Runtime\Runtime;
use Sample\Temporal\Runtime\RuntimeInterface;
use Temporal\OpenTelemetry\Tracer;
use Temporal\WorkerFactory;
use Temporal\Worker\WorkerFactoryInterface;
use Temporal\Worker\WorkerInterface;

use function env;

class RuntimeServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->scoped(Tracer::class, function() {
            $serviceName = env('OTEL_SERVICE_NAME', 'app-activity-worker');
            $collectorEndpoint = env('OTEL_COLLECTOR_ENDPOINT', 'http://collector.addr:4317');
            return RuntimeFactory::tracer($serviceName, $collectorEndpoint);
        });

        $this->app->scoped(WorkerFactory::class, fn() => WorkerFactory::create());
        $this->app->alias(WorkerFactory::class, WorkerFactoryInterface::class);

        $this->app->scoped(WorkerInterface::class, function() {
            $workerFactory = $this->app->make(WorkerFactoryInterface::class);
            $tracer = $this->app->make(Tracer::class);
            $activityTaskQueue = env('ACTIVITY_TASK_QUEUE', WorkerFactoryInterface::DEFAULT_TASK_QUEUE);
            return RuntimeFactory::worker($workerFactory, $tracer, $activityTaskQueue);
        });

        $this->app->scoped(Runtime::class, Runtime::class);
        $this->app->alias(Runtime::class, RuntimeInterface::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
