<?php

declare(strict_types=1);

namespace App\Temporal\Factory;

use Lagdo\Symfony\Facades\AbstractFacade;
use Temporal\Client\WorkflowClientInterface;

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
