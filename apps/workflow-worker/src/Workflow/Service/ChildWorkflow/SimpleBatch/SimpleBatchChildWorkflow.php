<?php

declare(strict_types=1);

namespace App\Workflow\Service\ChildWorkflow\SimpleBatch;

use App\Workflow\Service\Activity\SimpleBatch\SimpleBatchActivityFacade;
use Generator;

class SimpleBatchChildWorkflow implements SimpleBatchChildWorkflowInterface
{
    /**
     * @inheritDoc
     */
    public function processItem(int $itemId, int $batchId, array $options): Generator
    {
        // Set the item processing as started.
        yield SimpleBatchActivityFacade::itemProcessingStarted($itemId, $batchId, $options);

        // This activity randomly throws an exception.
        $output = yield SimpleBatchActivityFacade::processItem($itemId, $batchId, $options);

        // Set the item processing as ended.
        yield SimpleBatchActivityFacade::itemProcessingEnded($itemId, $batchId, $options);

        return $output;
    }
}
