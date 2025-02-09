<?php

declare(strict_types=1);

namespace App\Workflow\Service\ChildWorkflow\Greeting;

use Lagdo\Symfony\Facades\AbstractFacade;

/**
 * @extends AbstractFacade<GreetingChildWorkflowInterface>
 */
class GreetingChildWorkflowFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return GreetingChildWorkflowInterface::class;
    }
}
