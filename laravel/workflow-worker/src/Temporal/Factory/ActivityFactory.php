<?php

declare(strict_types=1);

namespace Sample\Temporal\Factory;

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
     * @param string $activityTaskQueue
     *
     * @return ActivityOptions
     */
    public static function simpleBatchOptions(string $activityTaskQueue): ActivityOptions
    {
        return ActivityOptions::new()
            ->withTaskQueue($activityTaskQueue)
            ->withStartToCloseTimeout(CarbonInterval::seconds(10))
            ->withScheduleToStartTimeout(CarbonInterval::seconds(10))
            ->withScheduleToCloseTimeout(CarbonInterval::minutes(30))
            ->withRetryOptions(
                RetryOptions::new()
                    ->withMaximumAttempts(100)
                    ->withInitialInterval(CarbonInterval::second(10))
                    ->withMaximumInterval(CarbonInterval::seconds(100))
            );
    }

    /**
     * Factory for activity stubs
     *
     * @template T of object
     * @psalm-param class-string<T> $activity
     * @param ActivityOptions $options
     *
     * @return T
     */
    public static function activityStub(string $activity, ActivityOptions $options): mixed
    {
        return Workflow::newActivityStub($activity, $options);
    }
}
