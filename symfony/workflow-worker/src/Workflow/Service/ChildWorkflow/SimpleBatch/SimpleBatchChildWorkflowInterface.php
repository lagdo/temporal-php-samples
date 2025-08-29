<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Workflow\Service\ChildWorkflow\SimpleBatch;

use Generator;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface SimpleBatchChildWorkflowInterface
{
    /**
     * @param int $itemId
     * @param int $batchId
     * @param array<string|int> $options
     *
     * @return Generator
     */
    #[WorkflowMethod(name: "SimpleBatch.processItem")]
    public function processItem(int $itemId, int $batchId, array $options): Generator;
}
