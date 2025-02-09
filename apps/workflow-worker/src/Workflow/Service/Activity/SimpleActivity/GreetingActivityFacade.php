<?php

declare(strict_types=1);

namespace App\Workflow\Service\Activity\SimpleActivity;

use Lagdo\Symfony\Facades\AbstractFacade;

/**
 * @extends AbstractFacade<GreetingActivityInterface>
 */
class GreetingActivityFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return GreetingActivityInterface::class;
    }
}
