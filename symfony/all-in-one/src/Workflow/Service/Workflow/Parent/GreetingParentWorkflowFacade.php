<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\Parent;

use App\Temporal\Facade\AbstractWorkflowFacade;

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
