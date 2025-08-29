<?php

declare(strict_types=1);

namespace Sample\Workflow\Service\ChildWorkflow\SimpleBatch;

use Sample\Workflow\Service\Activity\SimpleBatch\SimpleBatchActivityFacade;
use Generator;

class SimpleBatchChildWorkflow implements SimpleBatchChildWorkflowInterface
{
    /**
     * @inheritDoc
     */
    public function processItem(int $itemId, int $batchId, array $options): Generator
    {
        $simpleBatchActivity = SimpleBatchActivityFacade::instance();

        // Set the item processing as started.
        yield $simpleBatchActivity->itemProcessingStarted($itemId, $batchId, $options);

        // This activity randomly throws an exception.
        $output = yield $simpleBatchActivity->processItem($itemId, $batchId, $options);

        // Set the item processing as ended.
        yield $simpleBatchActivity->itemProcessingEnded($itemId, $batchId, $options);

        return $output;
    }
}
