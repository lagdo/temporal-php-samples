<?php

declare(strict_types=1);

namespace Sample\Workflow\Service\Workflow\MoneyBatch;

use App\Temporal\Facade\AbstractWorkflowFacade;

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
