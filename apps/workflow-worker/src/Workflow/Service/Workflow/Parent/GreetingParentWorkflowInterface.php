<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Workflow\Service\Workflow\Parent;

use Temporal\Workflow\WorkflowInterface;
use Temporal\Workflow\WorkflowMethod;

#[WorkflowInterface]
interface GreetingParentWorkflowInterface
{
    /**
     * @return []string
     */
    #[WorkflowMethod(name: "Parent.greet")]
    public function greet(string $name);
}
