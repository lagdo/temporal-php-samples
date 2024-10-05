<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\SimpleActivity;

use App\Workflow\Service\Activity\SimpleActivity\GreetingActivityFacade;

// @@@SNIPSTART php-hello-workflow
class GreetingWorkflow implements GreetingWorkflowInterface
{
    public function greet(string $name): \Generator
    {
        // This is a blocking call that returns only after the activity has completed.
        return yield GreetingActivityFacade::composeGreeting('Hello', $name);
    }
}
// @@@SNIPEND
