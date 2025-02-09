<?php

declare(strict_types=1);

namespace App\Workflow\Service\ChildWorkflow\SimpleBatch;

use Lagdo\Symfony\Facades\AbstractFacade;

/**
 * @extends AbstractFacade<SimpleBatchChildWorkflowInterface>
 */
class SimpleBatchChildWorkflowFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return SimpleBatchChildWorkflowInterface::class;
    }
}
