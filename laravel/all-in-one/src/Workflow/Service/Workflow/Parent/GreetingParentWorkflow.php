<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sample\Workflow\Service\Workflow\Parent;

use Sample\Workflow\Service\ChildWorkflow\Greeting\GreetingChildWorkflowFacade;
use Generator;

/**
 * Demonstrates a child workflow. Requires a local instance of the Temporal server to be running.
 */
class GreetingParentWorkflow implements GreetingParentWorkflowInterface
{
    /**
     * @inheritDoc
     */
    public function greet(string $name): Generator
    {
        // This is a non blocking call that returns immediately.
        // Use yield ChildWorkflowFacade::greet(name) to call synchronously.
        $childGreet = GreetingChildWorkflowFacade::greet($name);

        return "Hello $name from parent; " . yield $childGreet;
    }
}
