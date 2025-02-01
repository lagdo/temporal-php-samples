<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleActivity;

use App\Temporal\Factory\AbstractWorkflowFacade;

/**
 * @extends AbstractWorkflowFacade<GreetingWorkflowInterface>
 */
class GreetingWorkflowFacade extends AbstractWorkflowFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return GreetingWorkflowInterface::class;
    }
}
