<?php

namespace App\Service\SimpleBatch;

use Exception;
use Psr\Log\LoggerInterface;

use function array_map;
use function random_int;
use function range;
use function sleep;

class SimpleBatchService
{
    /**
     * @param LoggerInterface $logger
     */
    public function __construct(private LoggerInterface $logger)
    {}

    /**
     * @param int $batchId
     *
     * @return array
     */
    private function getItemIds(int $batchId): array
    {
        return array_map(function(int $itemId) use($batchId) {
            return (($batchId % 100) * 1000) + $itemId;
        }, range(1, random_int(100, 300)));
    }

    /**
     * @param int $batchId
     *
     * @return array
     */
    private function getOptions(int $batchId): array
    {
        return [];
    }

    /**
     * @param int $batchId
     *
     * @return array
     */
    public function getBatchItemIds(int $batchId): array
    {
        return [$this->getItemIds($batchId), $this->getOptions($batchId)];
    }

    /**
     * @param int $itemId
     * @param int $batchId
     * @param array $options
     *
     * @return void
     */
    public function itemProcessingStarted(int $itemId, int $batchId, array $options): void
    {
        $this->logger->debug("Started processing of item $itemId of batch $batchId.", ['options' => $options]);
    }

    /**
     * @param int $itemId
     * @param int $batchId
     * @param array $options
     *
     * @return void
     */
    public function processItem(int $itemId, int $batchId, array $options): void
    {
        $this->logger->debug("Processing item $itemId of batch $batchId.", ['options' => $options]);

        $random = random_int(0, 100);
        // Wait for max 2 seconds.
        sleep($random % 3);

        if($random > 50)
        {
            throw new Exception("Error while processing of item $itemId of batch $batchId.");
        }
    }

    /**
     * @param int $itemId
     * @param int $batchId
     * @param array $options
     *
     * @return void
     */
    public function itemProcessingEnded(int $itemId, int $batchId, array $options): void
    {
        $this->logger->debug("Ended processing of item $itemId of batch $batchId.", ['options' => $options]);
    }
}
