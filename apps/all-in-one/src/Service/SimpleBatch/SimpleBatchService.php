<?php

namespace App\Service\SimpleBatch;

use Exception;
use Lagdo\Facades\Logger;

use function array_map;
use function random_int;
use function range;
use function usleep;

class SimpleBatchService
{
    /**
     * @param int $batchId
     * @param int $minItemCount
     * @param int $maxItemCount
     *
     * @return array<int,int>
     */
    private function getItemIds(int $batchId, int $minItemCount, int $maxItemCount): array
    {
        return array_map(fn(int $itemId) => ($batchId % 100) * 1000 + $itemId,
            range(101, random_int(100 + $minItemCount, 100 + $maxItemCount)));
    }

    /**
     * @param int $batchId
     *
     * @return array<int,string|int>
     */
    private function getOptions(int $batchId): array
    {
        return [];
    }

    /**
     * @param int $batchId
     * @param int $minItemCount
     * @param int $maxItemCount
     *
     * @return array<array<int,string|int>>
     */
    public function getBatchItemIds(int $batchId, int $minItemCount = 20, int $maxItemCount = 50): array
    {
        return [
            $this->getItemIds($batchId, $minItemCount, $maxItemCount),
            $this->getOptions($batchId),
        ];
    }

    /**
     * @param int $itemId
     * @param int $batchId
     * @param array<int,string|int> $options
     *
     * @return void
     */
    public function itemProcessingStarted(int $itemId, int $batchId, array $options): void
    {
        Logger::debug("Started processing of item $itemId of batch $batchId.", ['options' => $options]);
    }

    /**
     * @param int $itemId
     * @param int $batchId
     * @param array<int,string|int> $options
     *
     * @return int
     * @throws Exception
     */
    public function processItem(int $itemId, int $batchId, array $options): int
    {
        Logger::debug("Processing item $itemId of batch $batchId.", ['options' => $options]);

        $random = random_int(0, 90);
        // Wait for max 1 second.
        usleep($random % 10000);

        if($random > 30)
        {
            throw new Exception("Error while processing of item $itemId of batch $batchId.");
        }
        return $random;
    }

    /**
     * @param int $itemId
     * @param int $batchId
     * @param array<int,string|int> $options
     *
     * @return void
     */
    public function itemProcessingEnded(int $itemId, int $batchId, array $options): void
    {
        Logger::debug("Ended processing of item $itemId of batch $batchId.", ['options' => $options]);
    }
}
