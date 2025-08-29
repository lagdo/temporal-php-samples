<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Sample\Workflow\Service\Workflow\SimpleActivity;

// @@@SNIPSTART php-hello-workflow-interface
use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;
use Generator;

#[WorkflowInterface]
interface GreetingWorkflowInterface
{
    /**
     * @param string $name
     *
     * @return Generator
     */
    #[WorkflowMethod(name: "SimpleActivity.greet")]
    public function greet(string $name): Generator;
}
// @@@SNIPEND
