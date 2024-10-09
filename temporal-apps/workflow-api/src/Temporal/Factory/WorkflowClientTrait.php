<?php

declare(strict_types=1);

namespace App\Temporal\Factory;

use Temporal\Workflow\WorkflowExecution;

trait WorkflowClientTrait
{
    /**
     * Get the facade service instance.
     *
     * @return mixed
     */
    abstract public static function instance();

    /**
     * Get the facade service identifier.
     *
     * @return string
     */
    abstract protected static function getServiceIdentifier(): string;

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
