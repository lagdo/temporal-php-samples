<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleBatch;

use Temporal\Workflow\QueryMethod;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface SimpleBatchWorkflowInterface
{
    #[WorkflowMethod(name: "SimpleBatch")]
    public function start(int $batchId);

    #[QueryMethod]
    public function getResults(): array;

    #[QueryMethod]
    public function getPending(): array;
}
