<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\MoneyBatch;

use App\Temporal\Factory\WorkflowClientTrait;
use Lagdo\Symfony\Facades\AbstractFacade;

class MoneyBatchWorkflowFacade extends AbstractFacade
{
    use WorkflowClientTrait;

    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return MoneyBatchWorkflowInterface::class;
    }
}
