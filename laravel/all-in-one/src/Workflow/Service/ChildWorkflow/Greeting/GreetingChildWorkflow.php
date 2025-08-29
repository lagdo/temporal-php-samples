<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sample\Workflow\Service\ChildWorkflow\Greeting;

class GreetingChildWorkflow implements GreetingChildWorkflowInterface
{
    public function greet(string $name): string
    {
        return "Hello $name from child workflow!";
    }
}
