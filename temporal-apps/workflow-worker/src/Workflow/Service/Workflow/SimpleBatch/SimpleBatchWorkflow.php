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
    private $results = [];

    /**
     * @var array
     */
    private $outputs = [];

    /**
     * @inheritDoc
     */
    public function start(int $batchId)
    {
        [$itemIds, $options] = yield SimpleBatchActivityFacade::getBatchItemIds($batchId);

        $promises = [];
        foreach($itemIds as $itemId)
        {
            $promises[$itemId] = Workflow::async(function() use($itemId, $batchId, $options) {
                // Set the item processing as started.
                yield SimpleBatchActivityFacade::itemProcessingStarted($itemId, $batchId, $options);

                // This activity randomly throws an exception.
                $output = yield SimpleBatchActivityFacade::processItem($itemId, $batchId, $options);

                // Set the item processing as ended.
                yield SimpleBatchActivityFacade::itemProcessingEnded($itemId, $batchId, $options);
    
                // Note: This will get the outputs as soon as they are available.
                $this->results[$itemId] = $output;

                return $output;
            });
            // $promises[$itemId] = SimpleBatchChildWorkflowFacade::processItem($itemId, $batchId, $options);
        }

        foreach($promises as $itemId => $promise)
        {
            // Note: This will get the outputs in the same order the tasks were started.
            $this->outputs[$itemId] = yield $promise;
        }

        return $this->outputs;
    }

    /**
     * @inheritDoc
     */
    public function getResults(): array
    {
        return $this->results;
    }

    /**
     * @inheritDoc
     */
    public function getOutputs(): array
    {
        return $this->outputs;
    }
}
