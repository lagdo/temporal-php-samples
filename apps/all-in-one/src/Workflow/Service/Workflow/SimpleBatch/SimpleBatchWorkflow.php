<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleBatch;

use App\Workflow\Service\Activity\SimpleBatch\SimpleBatchActivityFacade as ActivityFacade;
use React\Promise\FulfilledPromise;
use Temporal\Promise;
use Temporal\Workflow;
use Generator;
use Throwable;

use function array_filter;
use function array_keys;

class SimpleBatchWorkflow implements SimpleBatchWorkflowInterface
{
    /**
     * @var array<array<string|bool>>
     */
    private $results = [];

    /**
     * @var array<int,bool>
     */
    private $pending = [];

    /**
     * @inheritDoc
     */
    public function start(int $batchId): Generator
    {
        [$itemIds, $options] = yield ActivityFacade::getBatchItemIds($batchId);

        $promises = [];
        foreach($itemIds as $itemId)
        {
            $this->pending[$itemId] = true;
            /** @var FulfilledPromise */
            $promise = Workflow::async(
                function() use($itemId, $batchId, $options) {
                    // Set the item processing as started.
                    yield ActivityFacade::itemProcessingStarted($itemId, $batchId, $options);

                    // This activity randomly throws an exception.
                    $output = yield ActivityFacade::processItem($itemId, $batchId, $options);

                    // Set the item processing as ended.
                    yield ActivityFacade::itemProcessingEnded($itemId, $batchId, $options);

                    return $output;
                }
            )
            ->then(
                fn($output) => $this->results[$itemId] = [
                    'success' => true,
                    'output' => $output,
                ],
                fn(Throwable $e) => $this->results[$itemId] = [
                    'success' => false,
                    'message' => $e->getMessage(),
                ]
            );
            // We are calling always() instead of finally() because the Temporal PHP SDK depends on
            // react/promise 2.9. Will need to change to finally() when upgrading to react/promise 3.x.
            $promises[$itemId] = $promise->always(fn() => $this->pending[$itemId] = false);
            // $promises[$itemId] = SimpleBatchChildWorkflowFacade::processItem($itemId, $batchId, $options);
        }

        // Wait for all the async calls to terminate.
        yield Promise::all($promises);

        return $this->results;
    }

    /**
     * @inheritDoc
     */
    public function getAvailableResults(): array
    {
        return $this->results;
    }

    /**
     * @inheritDoc
     */
    public function getPendingTasks(): array
    {
        return array_keys(array_filter($this->pending, fn($pending) => $pending));
    }
}
