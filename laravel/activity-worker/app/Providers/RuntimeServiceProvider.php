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

        $this->app->scoped(WorkerFactory::class, fn() => WorkerFactory::create());
        $this->app->alias(WorkerFactory::class, WorkerFactoryInterface::class);

        $this->app->scoped(WorkerInterface::class, function() {
            $workerFactory = $this->app->make(WorkerFactoryInterface::class);
            $tracer = $this->app->make(Tracer::class);
            $activityTaskQueue = config('temporal.runtime.queue.activity');
            return RuntimeFactory::worker($workerFactory, $tracer, $activityTaskQueue);
        });

        $this->app->scoped(Runtime::class, Runtime::class);
        $this->app->alias(Runtime::class, RuntimeInterface::class);
    }
}
