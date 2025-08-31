<?php

declare(strict_types=1);

namespace App\Temporal\Facade;

use Lagdo\Facades\AbstractFacade;
use Temporal\Client\WorkflowClientInterface;

/**
 * @extends AbstractFacade<WorkflowClientInterface>
 */
class WorkflowClientFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return WorkflowClientInterface::class;
    }
}
