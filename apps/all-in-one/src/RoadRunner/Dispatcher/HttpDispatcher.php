<?php

namespace App\RoadRunner\Dispatcher;

use App\Kernel;
use Baldinof\RoadRunnerBundle\Runtime\Runtime;
use Symfony\Component\Runtime\SymfonyRuntime;
use Closure;

final class HttpDispatcher extends SymfonyDispatcher
{
    /**
     * @inheritDoc
     */
    protected function getRuntime(array $runtimeOptions): SymfonyRuntime
    {
        return new Runtime($runtimeOptions);
    }

    /**
     * @inheritDoc
     */
    protected function getResolverClosure(): Closure
    {
        return function(array $context) {
            return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
        };
    }
}
