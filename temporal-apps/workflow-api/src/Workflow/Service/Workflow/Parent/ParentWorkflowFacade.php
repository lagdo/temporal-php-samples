<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\Parent;

use App\Temporal\Factory\WorkflowClientTrait;
use Lagdo\Symfony\Facades\AbstractFacade;

class ParentWorkflowFacade extends AbstractFacade
{
    use WorkflowClientTrait;

    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return ParentWorkflowInterface::class;
    }
}
