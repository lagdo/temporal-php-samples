<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleBatch;

use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface SimpleBatchWorkflowInterface
{
    #[WorkflowMethod(name: "SimpleBatch")]
    public function start(int $batchId);
}
