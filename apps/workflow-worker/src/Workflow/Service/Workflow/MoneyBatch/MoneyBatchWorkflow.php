<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\MoneyBatch;

use App\Workflow\Service\Activity\MoneyBatch\AccountActivityFacade;
use Temporal\Common\Uuid;
use Temporal\Workflow;
use Generator;

class MoneyBatchWorkflow implements MoneyBatchWorkflowInterface
{
    /**
     * @var array<string>
     */
    private array $references = [];

    private int $balance = 0;

    private int $count = 0;

    public function deposit(string $toAccountId, int $batchSize): Generator
    {
        // wait for the completion of all transfers
        yield Workflow::await(fn() => $this->count === $batchSize);

        $referenceID = yield Workflow::sideEffect(fn() => Uuid::v4());

        yield AccountActivityFacade::deposit($toAccountId, $referenceID, $this->balance);
    }

    public function withdraw(string $fromAccountId, string $referenceId, int $amountCents): Generator
    {
        if (isset($this->references[$referenceId])) {
            // duplicate
            return;
        }
        $this->references[$referenceId] = $fromAccountId;

        yield AccountActivityFacade::withdraw($fromAccountId, $referenceId, $amountCents);

        $this->balance += $amountCents;
        $this->count++;
    }

    public function getBalance(): int
    {
        return $this->balance;
    }

    public function getCount(): int
    {
        return $this->count;
    }
}
