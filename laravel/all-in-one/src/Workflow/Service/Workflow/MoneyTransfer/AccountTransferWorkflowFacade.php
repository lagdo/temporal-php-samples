<?php

declare(strict_types=1);

namespace Sample\Workflow\Service\Workflow\MoneyTransfer;

use Sample\Temporal\Facade\AbstractWorkflowFacade;

/**
 * @extends AbstractWorkflowFacade<AccountTransferWorkflowInterface>
 */
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
