<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleBatch;

use App\Workflow\Service\Activity\SimpleBatch\SimpleBatchActivityFacade;
use Temporal\Promise;
use Temporal\Workflow;
use Throwable;

class SimpleBatchWorkflow implements SimpleBatchWorkflowInterface
{
    /**
     * @var array
     */
    private $results = [];

    /**
     * @var array
     */
    private $pending = [];

    /**
     * @inheritDoc
     */
    public function start(int $batchId)
    {
        [$itemIds, $options] = yield SimpleBatchActivityFacade::getBatchItemIds($batchId);

        $successCallback = function($output) {
            $this->results[] = [
                'success' => true,
                'output' => $output,
            ];
        };
        $errorCallback = function(Throwable $e) {
            $this->results[] = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
        };

        $promises = [];
        foreach($itemIds as $itemId)
        {
            $this->pending[$itemId] = true;
            $promises[$itemId] = Workflow::async(function() use($itemId, $batchId, $options) {
                // Set the item processing as started.
                yield SimpleBatchActivityFacade::itemProcessingStarted($itemId, $batchId, $options);

                // This activity randomly throws an exception.
                $output = yield SimpleBatchActivityFacade::processItem($itemId, $batchId, $options);

                // Set the item processing as ended.
                yield SimpleBatchActivityFacade::itemProcessingEnded($itemId, $batchId, $options);

                return $output;
            })
                ->then($successCallback, $errorCallback)
                // Calling always() instead of finally() because the Temporal PHP SDK depends on
                // react/promise 2.9. Will need to change to finally() when upgrading to react/promise 3.x.
                ->always(fn() => $this->pending[$itemId] = false);
            // $promises[$itemId] = SimpleBatchChildWorkflowFacade::processItem($itemId, $batchId, $options);
        }

        // Wait for all the async calls to terminate.
        yield Promise::all($promises);

        return $this->results;
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
    public function getPending(): array
    {
        return $this->pending;
    }
}
