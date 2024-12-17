<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\Child;

use Lagdo\Symfony\Facades\AbstractFacade;

class ChildWorkflowFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return ChildWorkflowInterface::class;
    }
}
