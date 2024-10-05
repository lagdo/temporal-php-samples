<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleActivity;

use Lagdo\Symfony\Facades\AbstractFacade;

class GreetingWorkflowFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return GreetingWorkflowInterface::class;
    }
}
