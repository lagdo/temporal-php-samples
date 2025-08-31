<?php

declare(strict_types=1);

namespace App\Workflow\Service\Activity\SimpleBatch;

use App\Service\SimpleBatch\SimpleBatchService;

class SimpleBatchActivity implements SimpleBatchActivityInterface
{
    public function __construct(private SimpleBatchService $simpleBatchService)
    {}

    /**
     * @inheritDoc
     */
    public function getBatchItemIds(int $batchId, int $minItemCount = 20, int $maxItemCount = 50): array
    {
        return $this->simpleBatchService->getBatchItemIds($batchId, $minItemCount, $maxItemCount);
    }

    /**
     * @inheritDoc
     */
    public function itemProcessingStarted(int $itemId, int $batchId, array $options): bool
    {
        $this->simpleBatchService->itemProcessingStarted($itemId, $batchId, $options);
        return true;
    }

    /**
     * @inheritDoc
     */
    public function processItem(int $itemId, int $batchId, array $options): int
    {
        return $this->simpleBatchService->processItem($itemId, $batchId, $options);
    }

    /**
     * @inheritDoc
     */
    public function itemProcessingEnded(int $itemId, int $batchId, array $options): bool
    {
        $this->simpleBatchService->itemProcessingEnded($itemId, $batchId, $options);
        return true;
    }
}
