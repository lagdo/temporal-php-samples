<?php

declare(strict_types=1);

namespace Sample\Temporal\Factory;

use Temporal\Activity\ActivityOptions;
use Temporal\Workflow;

class ActivityFactory
{
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
