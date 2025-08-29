<?php

declare(strict_types=1);

namespace Sample\Temporal\Factory;

use Carbon\CarbonInterval;
use Temporal\Client\WorkflowClientInterface;
use Temporal\Client\WorkflowOptions;

class WorkflowFactory
{
    /**
     * @param string $workflowTaskQueue
     *
     * @return WorkflowOptions
     */
    public static function defaultOptions(string $workflowTaskQueue): WorkflowOptions
    {
        return WorkflowOptions::new()
            ->withTaskQueue($workflowTaskQueue)
            ->withWorkflowExecutionTimeout(CarbonInterval::minute());
    }

    /**
     * @param string $workflowTaskQueue
     *
     * @return WorkflowOptions
     */
    public static function moneyBatchOptions(string $workflowTaskQueue): WorkflowOptions
    {
        return WorkflowOptions::new()
            ->withTaskQueue($workflowTaskQueue)
            ->withWorkflowExecutionTimeout(CarbonInterval::hour());
    }

    /**
     * @param string $workflowTaskQueue
     *
     * @return WorkflowOptions
     */
    public static function simpleBatchOptions(string $workflowTaskQueue): WorkflowOptions
    {
        return WorkflowOptions::new()
            ->withTaskQueue($workflowTaskQueue)
            ->withWorkflowExecutionTimeout(CarbonInterval::week());
    }

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
