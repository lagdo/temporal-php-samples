<?php

declare(strict_types=1);

namespace Sample\Workflow\Service\Workflow\Parent;

use Sample\Temporal\Facade\AbstractWorkflowFacade;

/**
 * @extends AbstractWorkflowFacade<GreetingParentWorkflowInterface>
 */
class GreetingParentWorkflowFacade extends AbstractWorkflowFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return GreetingParentWorkflowInterface::class;
    }
}
