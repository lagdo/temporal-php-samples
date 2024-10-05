<?php

declare(strict_types=1);

namespace App\Temporal\Factory;

use Carbon\CarbonInterval;
use Temporal\Activity\ActivityOptions;
use Temporal\Common\RetryOptions;
use Temporal\Workflow;

class ActivityFactory
{
    /**
     * @param string $activityTaskQueue
     *
     * @return ActivityOptions
     */
    public static function defaultOptions(string $activityTaskQueue): ActivityOptions
    {
        return ActivityOptions::new()
            ->withTaskQueue($activityTaskQueue)
            ->withStartToCloseTimeout(CarbonInterval::seconds(15))
            ->withRetryOptions(
                RetryOptions::new()->withMaximumAttempts(10)
            );
    }

    /**
     * @param string $activityTaskQueue
     *
     * @return ActivityOptions
     */
    public static function moneyBatchOptions(string $activityTaskQueue): ActivityOptions
    {
        return ActivityOptions::new()
            ->withTaskQueue($activityTaskQueue)
            ->withStartToCloseTimeout(CarbonInterval::seconds(15))
            ->withScheduleToCloseTimeout(CarbonInterval::hour(1))
            ->withRetryOptions(
                RetryOptions::new()
                    ->withMaximumAttempts(10)
                    ->withInitialInterval(CarbonInterval::second(1))
                    ->withMaximumInterval(CarbonInterval::seconds(10))
            );
    }

    /**
     * Factory for activity stubs
     *
     * @param string $activity
     * @param ActivityOptions $options
     *
     * @return mixed
     */
    public static function stub(string $activity, ActivityOptions $options): mixed
    {
        return Workflow::newActivityStub($activity, $options);
    }
}
