<?php

declare(strict_types=1);

namespace App\Temporal\Facade;

use Lagdo\Symfony\Facades\AbstractFacade;
use Temporal\Client\WorkflowClientInterface;

/**
 * @mixin WorkflowClientInterface
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
