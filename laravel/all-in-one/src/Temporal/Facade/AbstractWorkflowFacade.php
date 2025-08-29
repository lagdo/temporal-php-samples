<?php

declare(strict_types=1);

namespace Sample\Temporal\Facade;

use Lagdo\Facades\AbstractFacade;
use Temporal\Workflow\WorkflowExecution;

/**
 * @template WorkflowType of object
 * @extends AbstractFacade<WorkflowType>
 */
abstract class AbstractWorkflowFacade extends AbstractFacade
{
    /**
     * @param mixed $workflowArguments
     *
     * @return WorkflowExecution
     */
    public static function startWorkflow(...$workflowArguments): WorkflowExecution
    {
        // The `instance()` static method is implemented in the parent class.
        // It is called with the `self` keyword.
        return WorkflowClientFacade::start(self::instance(), ...$workflowArguments)->getExecution();
    }

    /**
     * @param string $workflowId
     *
     * @return WorkflowType
     */
    public static function getRunningWorkflow(string $workflowId): mixed
    {
        // The `getServiceIdentifier()` static method is implemented in a child class.
        // It is called with the `static` keyword.
        /** @psalm-var class-string<WorkflowType> $serviceIdentifier */
        $serviceIdentifier = static::getServiceIdentifier();
        return WorkflowClientFacade::newRunningWorkflowStub($serviceIdentifier, $workflowId);
    }
}
