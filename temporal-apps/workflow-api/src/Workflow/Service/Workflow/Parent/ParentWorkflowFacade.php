<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\Parent;

use App\Temporal\Factory\AbstractWorkflowFacade;

class ParentWorkflowFacade extends AbstractWorkflowFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return ParentWorkflowInterface::class;
    }
}
