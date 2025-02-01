<?php

declare(strict_types=1);

namespace App\Temporal\Facade;

use Lagdo\Symfony\Facades\AbstractFacade;
use Temporal\Workflow\WorkflowExecution;

/**
 * @template WorkflowType
 */
abstract class AbstractWorkflowFacade extends AbstractFacade
{
    /**
     * @param array $workflowArguments
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
    public static function getRunningWorkflow(string $workflowId)
    {
        // The `getServiceIdentifier()` static method is implemented in a child class.
        // It is called with the `static` keyword.
        return WorkflowClientFacade::newRunningWorkflowStub(static::getServiceIdentifier(), $workflowId);
    }
}
