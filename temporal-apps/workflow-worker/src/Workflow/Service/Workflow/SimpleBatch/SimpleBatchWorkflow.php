<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleBatch;

use App\Workflow\Service\Activity\SimpleBatch\SimpleBatchActivityFacade;
use Temporal\Workflow;

class SimpleBatchWorkflow implements SimpleBatchWorkflowInterface
{
    /**
     * @var array
     */
    private $outputs = [];

    public function start(int $batchId)
    {
        [$itemIds, $options] = yield SimpleBatchActivityFacade::getBatchItemIds($batchId);

        $handles = [];
        foreach($itemIds as $itemId)
        {
            $handles[$itemId] = Workflow::async(function() use($itemId, $batchId, $options) {
                // Set the item processing as started.
                yield SimpleBatchActivityFacade::itemProcessingStarted($itemId, $batchId, $options);

                $output = yield SimpleBatchActivityFacade::processItem($itemId, $batchId, $options);

                // Set the item processing as ended.
                yield SimpleBatchActivityFacade::itemProcessingEnded($itemId, $batchId, $options);
    
                return $output;
            });
            // $handles[$itemId] = SimpleBatchChildWorkflowFacade::processItem($itemId, $batchId, $options);
        }

        foreach($handles as $itemId => $handle)
        {
            $this->outputs[$itemId] = yield $handle;
        }

        return $this->outputs;
    }

    public function getOutputs(): array
    {
        return $this->outputs;
    }
}
