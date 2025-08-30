<?php

declare(strict_types=1);

namespace Sample\Temporal\Factory;

use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\WorkflowOptions;

class WorkflowFactory
{
    /**
     * Factory for workflow stubs
     *
     * @template T of object
     * @psalm-param class-string<T> $workflow
     * @param WorkflowOptions $options
     * @param WorkflowClientInterface $workflowClient
     *
     * @return T
     */
    public static function workflowStub(string $workflow, WorkflowOptions $options,
        WorkflowClientInterface $workflowClient): mixed
    {
        return $workflowClient->newWorkflowStub($workflow, $options);
    }
}
