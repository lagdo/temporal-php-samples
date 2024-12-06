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
     * @return array
     */
    public function getBatchItemIds(int $batchId): array;

    /**
     * @param int $itemId
     * @param int $batchId
     * @param array $options
     *
     * @return void
     */
    public function itemProcessingStarted(int $itemId, int $batchId, array $options): bool;

    /**
     * @param int $itemId
     * @param int $batchId
     * @param array $options
     *
     * @return void
     */
    public function processItem(int $itemId, int $batchId, array $options): bool;

    /**
     * @param int $itemId
     * @param int $batchId
     * @param array $options
     *
     * @return void
     */
    public function itemProcessingEnded(int $itemId, int $batchId, array $options): bool;
}
