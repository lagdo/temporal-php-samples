<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Support\ServiceProvider;
use Temporal\Client\WorkflowOptions;

use function config;

class OptionsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Default workflow options
        $this->app->scoped('defaultWorkflowOptions', function(): WorkflowOptions {
            return WorkflowOptions::new()
                ->withTaskQueue(config('temporal.runtime.queue.workflow'))
                ->withWorkflowExecutionTimeout(CarbonInterval::minute());
        });

        // Money batch workflow options
        $this->app->scoped('moneyBatchWorkflowOptions', function(): WorkflowOptions {
            return WorkflowOptions::new()
                ->withTaskQueue(config('temporal.runtime.queue.workflow'))
                ->withWorkflowExecutionTimeout(CarbonInterval::hour());
        });

        // Simple batch workflow options
        $this->app->scoped('simpleBatchWorkflowOptions', function(): WorkflowOptions {
            return WorkflowOptions::new()
                ->withTaskQueue(config('temporal.runtime.queue.workflow'))
                ->withWorkflowExecutionTimeout(CarbonInterval::week());
        });
    }
}
