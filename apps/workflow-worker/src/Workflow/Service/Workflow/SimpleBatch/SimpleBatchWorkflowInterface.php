<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleBatch;

use Temporal\Workflow\QueryMethod;
use Temporal\Workflow\ReturnType;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;
use Generator;

#[WorkflowInterface]
interface SimpleBatchWorkflowInterface
{
    /**
     * @param int $batchId
     *
     * @return Generator
     */
    #[WorkflowMethod(name: "SimpleBatch")]
    #[ReturnType("string")]
    public function start(int $batchId): Generator;

    /**
     * @return array<array<string|bool>>
     */
    #[QueryMethod]
    public function getResults(): array;

    /**
     * @return array<int>
     */
    #[QueryMethod]
    public function getPending(): array;
}
