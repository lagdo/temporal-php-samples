<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\MoneyTransfer;

use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;
use Generator;

#[WorkflowInterface]
interface AccountTransferWorkflowInterface
{
    /**
     * @param string $fromAccountId
     * @param string $toAccountId
     * @param string $referenceId
     * @param int $amountCents
     *
     * @return Generator
     */
    #[WorkflowMethod(name: "MoneyTransfer")]
    public function transfer(string $fromAccountId, string $toAccountId, string $referenceId, int $amountCents): Generator;
}
