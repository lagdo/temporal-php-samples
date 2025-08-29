<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sample\Workflow\Service\Workflow\MoneyTransfer;

use Sample\Workflow\Service\Activity\MoneyTransfer\AccountActivityFacade;
use Generator;

class AccountTransferWorkflow implements AccountTransferWorkflowInterface
{
    /**
     * @inheritDoc
     */
    public function transfer(string $fromAccountId, string $toAccountId,
        string $referenceId, int $amountCents): Generator
    {
        $account = AccountActivityFacade::instance();

        yield $account->withdraw($fromAccountId, $referenceId, $amountCents);
        yield $account->deposit($toAccountId, $referenceId, $amountCents);
    }
}
