<?php

declare(strict_types=1);

namespace Sample\Temporal\Factory;

use Temporal\Workflow\ChildWorkflowOptions;
use Temporal\Workflow;

class ChildWorkflowFactory
{
    /**
     * Factory for workflow stubs
     *
     * @template T of object
     * @psalm-param class-string<T> $workflow
     * @param ChildWorkflowOptions $options
     *
     * @return T
     */
    public static function childWorkflowStub(string $workflow, ChildWorkflowOptions $options): mixed
    {
        return Workflow::newChildWorkflowStub($workflow, $options);
    }
}
