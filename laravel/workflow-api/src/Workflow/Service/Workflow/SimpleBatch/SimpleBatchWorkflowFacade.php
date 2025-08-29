<?php

declare(strict_types=1);

namespace Sample\Workflow\Service\Workflow\SimpleBatch;

use Sample\Temporal\Facade\AbstractWorkflowFacade;

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
