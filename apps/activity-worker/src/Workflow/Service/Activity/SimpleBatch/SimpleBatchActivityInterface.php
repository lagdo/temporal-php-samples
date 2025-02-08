<?php

declare(strict_types=1);

namespace App\Workflow\Service\Activity\SimpleBatch;

use Temporal\Activity\ActivityInterface;

#[ActivityInterface(prefix: "SimpleBatch.")]
interface SimpleBatchActivityInterface
{
    /**
     * @param int $batchId
     *
     * @return array<array<int,string|int>>
     */
    public function getBatchItemIds(int $batchId): array;

    /**
     * @param int $itemId
     * @param int $batchId
     * @param array<string|int> $options
     *
     * @return bool
     */
    public function itemProcessingStarted(int $itemId, int $batchId, array $options): bool;

    /**
     * @param int $itemId
     * @param int $batchId
     * @param array<string|int> $options
     *
     * @return int
     */
    public function processItem(int $itemId, int $batchId, array $options): int;

    /**
     * @param int $itemId
     * @param int $batchId
     * @param array<string|int> $options
     *
     * @return bool
     */
    public function itemProcessingEnded(int $itemId, int $batchId, array $options): bool;
}
