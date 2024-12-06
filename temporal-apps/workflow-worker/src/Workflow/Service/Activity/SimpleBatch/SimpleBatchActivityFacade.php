<?php

declare(strict_types=1);

namespace App\Workflow\Service\Activity\SimpleBatch;

use Lagdo\Symfony\Facades\AbstractFacade;

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
