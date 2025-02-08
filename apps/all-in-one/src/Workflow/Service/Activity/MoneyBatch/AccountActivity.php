<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Workflow\Service\Activity\MoneyBatch;

use App\Service\MoneyBatch\MoneyBatchService;

class AccountActivity implements AccountActivityInterface
{
    /**
     * @param MoneyBatchService $moneyBatchService
     */
    public function __construct(private MoneyBatchService $moneyBatchService)
    {}

    /**
     * @inheritDoc
     */
    public function deposit(string $accountId, string $referenceId, int $amountCents): int
    {
        $this->moneyBatchService->deposit($accountId, $referenceId, $amountCents);
        return $amountCents;
    }

    /**
     * @inheritDoc
     */
    public function withdraw(string $accountId, string $referenceId, int $amountCents): int
    {
        $this->moneyBatchService->withdraw($accountId, $referenceId, $amountCents);
        return $amountCents;
    }
}
