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
     * @param string $accountId
     * @param string $referenceId
     * @param int $amountCents
     *
     * @return void
     */
    public function deposit(string $accountId, string $referenceId, int $amountCents): void
    {
        $this->moneyBatchService->deposit($accountId, $referenceId, $amountCents);
    }

    /**
     * @param string $accountId
     * @param string $referenceId
     * @param int $amountCents
     *
     * @return void
     */
    public function withdraw(string $accountId, string $referenceId, int $amountCents): void
    {
        $this->moneyBatchService->withdraw($accountId, $referenceId, $amountCents);
    }
}
