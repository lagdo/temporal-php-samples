<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Workflow\Service\Activity\MoneyTransfer;

use App\Service\MoneyTransfer\MoneyTransferService;

class AccountActivity implements AccountActivityInterface
{
    /**
     * @param MoneyTransferService $moneyTransferService
     */
    public function __construct(private MoneyTransferService $moneyTransferService)
    {}

    /**
     * @inheritDoc
     */
    public function deposit(string $accountId, string $referenceId, int $amountCents): int
    {
        $this->moneyTransferService->deposit($accountId, $referenceId, $amountCents);
        return $amountCents;
    }

    /**
     * @inheritDoc
     */
    public function withdraw(string $accountId, string $referenceId, int $amountCents): int
    {
        $this->moneyTransferService->withdraw($accountId, $referenceId, $amountCents);
        return $amountCents;
    }
}
