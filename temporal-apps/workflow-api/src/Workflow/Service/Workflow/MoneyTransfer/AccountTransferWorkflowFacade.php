<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\MoneyTransfer;

use App\Temporal\Factory\WorkflowClientTrait;
use Lagdo\Symfony\Facades\AbstractFacade;

class AccountTransferWorkflowFacade extends AbstractFacade
{
    use WorkflowClientTrait;

    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return AccountTransferWorkflowInterface::class;
    }
}
