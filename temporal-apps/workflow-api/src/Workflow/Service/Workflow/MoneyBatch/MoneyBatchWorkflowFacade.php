<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\MoneyBatch;

use Lagdo\Symfony\Facades\AbstractFacade;

class MoneyBatchWorkflowFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return MoneyBatchWorkflowInterface::class;
    }
}
