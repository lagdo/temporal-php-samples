<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleBatch;

use App\Temporal\Attribute\WorkflowOptions;
use Temporal\Workflow\QueryMethod;
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
#[WorkflowOptions(serviceId: "simpleBatchWorkflowOptions")]
interface SimpleBatchWorkflowInterface
{
    #[WorkflowMethod(name: "SimpleBatch")]
    public function start(int $batchId);

    #[QueryMethod]
    public function getOutputs(): array;
}
