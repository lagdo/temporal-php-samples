<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\MoneyTransfer;

use App\Temporal\Factory\AbstractWorkflowFacade;

class AccountTransferWorkflowFacade extends AbstractWorkflowFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return AccountTransferWorkflowInterface::class;
    }
}
