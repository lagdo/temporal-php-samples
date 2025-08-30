<?php

namespace App\Providers;

use Carbon\CarbonInterval;
use Illuminate\Support\ServiceProvider;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Workflow\ChildWorkflowOptions;

use function env;

class OptionsServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Default child workflow options
        $this->app->scoped('defaultChildWorkflowOptions', function(): ChildWorkflowOptions {
            return ChildWorkflowOptions::new()
                ->withTaskQueue(env('WORKFLOW_TASK_QUEUE'));
        });

        // Default activity options
        $this->app->scoped('defaultActivityOptions', function(): ActivityOptions {
            return ActivityOptions::new()
                ->withTaskQueue(env('ACTIVITY_TASK_QUEUE'))
                ->withStartToCloseTimeout(CarbonInterval::seconds(15))
                ->withRetryOptions(
                    RetryOptions::new()->withMaximumAttempts(10)
                );
        });

        // Money batch activity options
        $this->app->scoped('moneyBatchActivityOptions', function(): ActivityOptions {
            return ActivityOptions::new()
                ->withTaskQueue(env('ACTIVITY_TASK_QUEUE'))
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
                ->withTaskQueue(env('ACTIVITY_TASK_QUEUE'))
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
