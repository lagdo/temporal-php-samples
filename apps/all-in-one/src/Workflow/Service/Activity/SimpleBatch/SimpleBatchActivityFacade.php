<?php

declare(strict_types=1);

namespace App\Workflow\Service\Activity\SimpleBatch;

use Lagdo\Symfony\Facades\AbstractFacade;

/**
 * @method static array getBatchItemIds(int $batchId)
 * @method static bool itemProcessingStarted(int $itemId, int $batchId, array $options)
 * @method static int processItem(int $itemId, int $batchId, array $options)
 * @method static bool itemProcessingEnded(int $itemId, int $batchId, array $options)
 */
class SimpleBatchActivityFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return SimpleBatchActivityInterface::class;
    }
}
