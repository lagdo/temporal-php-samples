<?php

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleBatch;

use Lagdo\Symfony\Facades\AbstractFacade;

class SimpleBatchChildWorkflowFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return SimpleBatchChildWorkflowInterface::class;
    }
}
