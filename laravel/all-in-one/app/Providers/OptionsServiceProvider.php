<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Support\ServiceProvider;
use Temporal\Client\WorkflowOptions;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Workflow\ChildWorkflowOptions;

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
                ->withTaskQueue(config('temporal.runtime.queue.default'))
                ->withWorkflowExecutionTimeout(CarbonInterval::minute());
        });

        // Money batch workflow options
        $this->app->scoped('moneyBatchWorkflowOptions', function(): WorkflowOptions {
            return WorkflowOptions::new()
                ->withTaskQueue(config('temporal.runtime.queue.default'))
                ->withWorkflowExecutionTimeout(CarbonInterval::hour());
        });

        // Simple batch workflow options
        $this->app->scoped('simpleBatchWorkflowOptions', function(): WorkflowOptions {
            return WorkflowOptions::new()
                ->withTaskQueue(config('temporal.runtime.queue.default'))
                ->withWorkflowExecutionTimeout(CarbonInterval::week());
        });

        // Default child workflow options
        $this->app->scoped('defaultChildWorkflowOptions', function(): ChildWorkflowOptions {
            return ChildWorkflowOptions::new()
                ->withTaskQueue(config('temporal.runtime.queue.default'));
        });

        // Default activity options
        $this->app->scoped('defaultActivityOptions', function(): ActivityOptions {
            return ActivityOptions::new()
                ->withTaskQueue(config('temporal.runtime.queue.default'))
                ->withStartToCloseTimeout(CarbonInterval::seconds(15))
                ->withRetryOptions(
                    RetryOptions::new()->withMaximumAttempts(10)
                );
        });

        // Money batch activity options
        $this->app->scoped('moneyBatchActivityOptions', function(): ActivityOptions {
            return ActivityOptions::new()
                ->withTaskQueue(config('temporal.runtime.queue.default'))
                ->withStartToCloseTimeout(CarbonInterval::seconds(15))
                ->withScheduleToCloseTimeout(CarbonInterval::hour(1))
                ->withRetryOptions(
                    RetryOptions::new()
                        ->withMaximumAttempts(10)
                        ->withInitialInterval(CarbonInterval::second(1))
                        ->withMaximumInterval(CarbonInterval::seconds(10))
                );
        });

        // Simple batch activity options
        $this->app->scoped('simpleBatchActivityOptions', function(): ActivityOptions {
            return ActivityOptions::new()
                ->withTaskQueue(config('temporal.runtime.queue.default'))
                ->withStartToCloseTimeout(CarbonInterval::seconds(10))
                ->withScheduleToStartTimeout(CarbonInterval::seconds(10))
                ->withScheduleToCloseTimeout(CarbonInterval::minutes(30))
                ->withRetryOptions(
                    RetryOptions::new()
                        ->withMaximumAttempts(100)
                        ->withInitialInterval(CarbonInterval::second(10))
                        ->withMaximumInterval(CarbonInterval::seconds(100))
                );
        });
    }
}
