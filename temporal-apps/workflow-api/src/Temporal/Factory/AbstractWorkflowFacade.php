<?php

declare(strict_types=1);

namespace App\Temporal\Factory;

use Lagdo\Symfony\Facades\AbstractFacade;
use Temporal\Workflow\WorkflowExecution;

abstract class AbstractWorkflowFacade extends AbstractFacade
{
    /**
     * @param array $workflowArguments
     *
     * @return WorkflowExecution
     */
    public static function startWorkflow(...$workflowArguments): WorkflowExecution
    {
        return WorkflowClientFacade::start(self::instance(), ...$workflowArguments)->getExecution();
    }

    /**
     * @param string $workflowId
     *
     * @return object
     */
    public static function getRunningWorkflow(string $workflowId): object
    {
        return WorkflowClientFacade::newRunningWorkflowStub(self::getServiceIdentifier(), $workflowId);
    }
}
