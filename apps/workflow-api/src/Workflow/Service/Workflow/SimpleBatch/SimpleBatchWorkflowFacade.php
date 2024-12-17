<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleBatch;

use App\Temporal\Factory\AbstractWorkflowFacade;

/**
 * @extends AbstractWorkflowFacade<SimpleBatchWorkflowInterface>
 */
class SimpleBatchWorkflowFacade extends AbstractWorkflowFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return SimpleBatchWorkflowInterface::class;
    }
}
