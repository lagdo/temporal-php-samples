<?php

declare(strict_types=1);

namespace App\Workflow\Service\Activity\MoneyBatch;

use Lagdo\Symfony\Facades\AbstractFacade;

/**
 * @mixin AccountActivityInterface
 */
class AccountActivityFacade extends AbstractFacade
{
    /**
     * @inheritDoc
     */
    protected static function getServiceIdentifier(): string
    {
        return AccountActivityInterface::class;
    }
}
