<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\Parent;

use App\Temporal\Factory\AbstractWorkflowFacade;

/**
 * @extends AbstractWorkflowFacade<ParentWorkflowInterface>
 */
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
