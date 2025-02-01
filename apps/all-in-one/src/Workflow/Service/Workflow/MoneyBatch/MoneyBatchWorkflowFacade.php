<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\MoneyBatch;

use App\Temporal\Factory\AbstractWorkflowFacade;

/**
 * @extends AbstractWorkflowFacade<MoneyBatchWorkflowInterface>
 */
class MoneyBatchWorkflowFacade extends AbstractWorkflowFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return MoneyBatchWorkflowInterface::class;
    }
}
