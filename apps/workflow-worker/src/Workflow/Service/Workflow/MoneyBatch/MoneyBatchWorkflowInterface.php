<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\MoneyBatch;

use Temporal\Workflow\QueryMethod;
use Temporal\Workflow\SignalMethod;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;
use Generator;

#[WorkflowInterface]
interface MoneyBatchWorkflowInterface
{
    /**
     * @return Generator
     */
    #[WorkflowMethod(name: "MoneyBatch")]
    public function deposit(string $toAccountId, int $batchSize): Generator;

    /**
     * @return Generator
     */
    #[SignalMethod]
    public function withdraw(string $fromAccountId, string $referenceId, int $amountCents): Generator;

    /**
     * @return int
     */
    #[QueryMethod]
    public function getBalance(): int;

    /**
     * @return int
     */
    #[QueryMethod]
    public function getCount(): int;
}
