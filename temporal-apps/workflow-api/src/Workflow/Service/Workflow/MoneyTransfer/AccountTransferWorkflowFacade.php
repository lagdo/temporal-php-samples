<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\MoneyTransfer;

use Lagdo\Symfony\Facades\AbstractFacade;

class AccountTransferWorkflowFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return AccountTransferWorkflowInterface::class;
    }
}
