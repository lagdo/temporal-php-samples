<?php

/**
 * This file is part of Temporal package.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Workflow\Service\Activity\SimpleActivity;

use App\Service\SimpleActivity\SimpleActivityService;

// @@@SNIPSTART php-hello-activity
class GreetingActivity implements GreetingActivityInterface
{
    /**
     * @param SimpleActivityService $simpleActivityService
     */
    public function __construct(private SimpleActivityService $simpleActivityService)
    {}

    /**
     * @param string $greeting
     * @param string $name
     *
     * @return string
     */
    public function composeGreeting(string $greeting, string $name): string
    {
        return $this->simpleActivityService->greet($greeting, $name);
    }
}
// @@@SNIPEND
