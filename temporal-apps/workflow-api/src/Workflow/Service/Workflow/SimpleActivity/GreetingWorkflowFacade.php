<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleActivity;

use App\Temporal\Factory\WorkflowClientTrait;
use Lagdo\Symfony\Facades\AbstractFacade;

class GreetingWorkflowFacade extends AbstractFacade
{
    use WorkflowClientTrait;

    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return GreetingWorkflowInterface::class;
    }
}
