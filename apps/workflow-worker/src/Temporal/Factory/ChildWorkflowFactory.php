<?php

declare(strict_types=1);

namespace App\Temporal\Factory;

use Temporal\Workflow\ChildWorkflowOptions;
use Temporal\Workflow;

class ChildWorkflowFactory
{
    /**
     * @param string $workflowTaskQueue
     *
     * @return ChildWorkflowOptions
     */
    public static function defaultOptions(string $workflowTaskQueue): ChildWorkflowOptions
    {
        return ChildWorkflowOptions::new()
            ->withTaskQueue($workflowTaskQueue);
    }

    /**
     * Factory for workflow stubs
     *
     * @param string $workflow
     * @param ChildWorkflowOptions $options
     *
     * @return mixed
     */
    public static function childWorkflowStub(string $workflow, ChildWorkflowOptions $options): mixed
    {
        return Workflow::newChildWorkflowStub($workflow, $options);
    }
}
