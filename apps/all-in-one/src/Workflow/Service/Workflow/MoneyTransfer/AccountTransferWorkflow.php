<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\MoneyTransfer;

use App\Workflow\Service\Activity\MoneyTransfer\AccountActivityFacade;
use App\Workflow\Service\Activity\MoneyTransfer\AccountActivityInterface;
use Generator;

class AccountTransferWorkflow implements AccountTransferWorkflowInterface
{
    /**
     * @inheritDoc
     */
    public function transfer(string $fromAccountId, string $toAccountId, string $referenceId, int $amountCents): Generator
    {
        /**
         * @var AccountActivityInterface
         */
        $account = AccountActivityFacade::instance();

        yield $account->withdraw($fromAccountId, $referenceId, $amountCents);
        yield $account->deposit($toAccountId, $referenceId, $amountCents);
    }
}
