<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleBatch;

use App\Workflow\Service\Activity\SimpleBatch\SimpleBatchActivityFacade;

class SimpleBatchChildWorkflow implements SimpleBatchChildWorkflowInterface
{
    /**
     * @inheritDoc
     */
    public function processItem(int $itemId, int $batchId, array $options)
    {
        // Set the item processing as started.
        yield SimpleBatchActivityFacade::itemProcessingStarted($itemId, $batchId, $options);

        $output = yield SimpleBatchActivityFacade::processItem($itemId, $batchId, $options);

        // Set the item processing as ended.
        yield SimpleBatchActivityFacade::itemProcessingEnded($itemId, $batchId, $options);

        return $output;
    }
}
