<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\Parent;

use Lagdo\Symfony\Facades\AbstractFacade;

class ParentWorkflowFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return ParentWorkflowInterface::class;
    }
}
