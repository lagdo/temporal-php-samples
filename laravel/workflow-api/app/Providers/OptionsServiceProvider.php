<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Support\ServiceProvider;
use Temporal\Client\WorkflowOptions;

use function env;

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
                ->withTaskQueue(env('WORKFLOW_TASK_QUEUE'))
                ->withWorkflowExecutionTimeout(CarbonInterval::minute());
        });

        // Money batch workflow options
        $this->app->scoped('moneyBatchWorkflowOptions', function(): WorkflowOptions {
            return WorkflowOptions::new()
                ->withTaskQueue(env('WORKFLOW_TASK_QUEUE'))
                ->withWorkflowExecutionTimeout(CarbonInterval::hour());
        });

        // Simple batch workflow options
        $this->app->scoped('simpleBatchWorkflowOptions', function(): WorkflowOptions {
            return WorkflowOptions::new()
                ->withTaskQueue(env('WORKFLOW_TASK_QUEUE'))
                ->withWorkflowExecutionTimeout(CarbonInterval::week());
        });
    }
}
